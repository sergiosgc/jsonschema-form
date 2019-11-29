
export default class SelectLoader {
    static setup() {
        if (document.readyState == 'loading') {
            window.addEventListener('load', SelectLoader.setup);
            return;
        }
        SelectLoader.bind();
    }
    static bind() {
        Array.from(document.getElementsByTagName('SELECT')).map(
            elm => elm as HTMLSelectElement
        ).filter(
            function (elm: HTMLSelectElement) {
                if ('string' != typeof (elm as HTMLElement).dataset.updateCallback) return false;
                if ('string' != typeof (elm as HTMLElement).dataset.updateDependsOn) return false;
                return true;
            }
        ).map(SelectLoader.bindElement);
    }
    static bindElement(elm: HTMLSelectElement) {
        let form = elm.form!;
        JSON.parse(elm.dataset.updateDependsOn!).map(
            (dep: string) => Array.from(form.elements).filter( (formElement: any) => formElement.name == dep ) 
        ).reduce( (acc: Array<HTMLElement>, arr: Array<HTMLElement>) => acc.concat(arr), [] 
        ).map( function(dep: HTMLElement) {
            if (dep.dataset.selectLoaderHandlersAdded) return null;
            dep.dataset.selectLoaderHandlersAdded = "true";
            let updateFunction = SelectLoader.executeUpdate.bind(window, elm, dep);
            dep.addEventListener('input', updateFunction);
            return updateFunction;
        }).filter( 
            (f: Function|null) => f 
        ).map(
            (f: Function) => f() 
        );
    }
    static async executeUpdate(toUpdate: HTMLSelectElement, updateSource: HTMLElement) {
        let url: string = toUpdate.dataset.updateCallback!;
        let argumentRe = /%<([^>]*)>/;
        let match: any;
        while (match = argumentRe.exec(url)) {
            if ("undefined" == typeof toUpdate.form![ match[1] ]) {
                console.error("Form contains no argument '" + match[1] + "' when assembling update URL '" + toUpdate.dataset.updateCallback! + "'");
                return;
            }
            let value = (Array.from(toUpdate.form!.elements).filter( (elm: any) => elm.name == match[1] && !elm.disabled )[0] as any).value;
            url = url.replace(match[0], value);
        }
        let response;
        try {
            response = await fetch(url, { credentials: 'same-origin' });
        } catch (error) {
            console.error("Error fetching data from '" + url + "': " + error);
            return;
        }
        if (response.status != 200) {
            console.error("Error fetching data from '" + url + "': Status code: " + response.status + " Status text: " + response.statusText);
            return;
        }
        let json = await response.json();
        if ("undefined" == typeof toUpdate.dataset.updateLabel) {
            console.error("data-update-label property not set on ", toUpdate);
            return;
        }
        let label: string = toUpdate.dataset.updateLabel;
        if ("undefined" == typeof toUpdate.dataset.updateValue) {
            console.error("data-update-value property not set on ", toUpdate);
            return;
        }
        let value: string = toUpdate.dataset.updateValue;
        if (!('data' in json)) {
            console.error("Update callback result contains no 'data' field");
            return;
        }
        if (!('collection' in json['data'])) {
            console.error("Update callback result contains no 'data.collection' field");
            return;
        }
        let options = json['data']['collection'].reduce(
            function (acc: Array<any>, val: any) {
                acc.push( { value: val[value], label: val[label] } );
                return acc;
            },
            toUpdate.getAttribute('placeholder') ? [ { value: '', label: toUpdate.getAttribute('placeholder') } ] : []
        );
        let currentValue = toUpdate.value;
        if (options.length && ! (options.reduce( (acc: boolean, val: any) => val['value'] == currentValue ? true : acc, false))) currentValue = options[0]['value'];
        while (toUpdate.firstChild) toUpdate.removeChild(toUpdate.firstChild);
        options.map(function(o:any) {
            let option = toUpdate.appendChild(document.createElement('OPTION'));
            option.setAttribute('value', o['value']);
            if (currentValue == o['value']) option.setAttribute('selected', 'selected');
            option.appendChild(document.createTextNode(o['label']));
        });
    }
}