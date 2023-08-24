import XPathObserver from "/home/sergio/Projects/Sooma/Blog.com/directory/private/js/src/xpath-observer/src/index";

export default class FormWizard {
    static setup() {
        const observer = new XPathObserver("//form[count(./fieldset[contains(@class, 'wizard-step')]) > 1]");
        observer.addEventListener("xpathobserver.node.new", FormWizard.handleNewForm);
        observer.addEventListener("xpathobserver.node.deleted", FormWizard.handleNewForm);

    }
    static focusFirstInput(fieldSets: HTMLElement[]): void {
        const activeFieldSet = fieldSets.find( fs => window.getComputedStyle(fs).display != "none" );
        if (!activeFieldSet) return;
        Array.from(document.evaluate(".//*[self::input or self::textarea]", activeFieldSet, null, XPathResult.FIRST_ORDERED_NODE_TYPE))
            .forEach( input => input.focus() );
    }
    static handleNewForm(ev: Event) {
        const cev = ev as CustomEvent
        const form = cev.detail.target;
        const fieldSets = Array
            .from(document.evaluate("./fieldset", form))
            .filter( fs => fs.classList.contains('wizard-step') );
        if ( 0 == fieldSets.length ) return;
        (Array
            .from(fieldSets[0].getElementsByClassName('wizard')) as HTMLElement[])
            .filter( n => n instanceof HTMLInputElement)
            .filter( input => input.getAttribute('name') == 'back')
            .forEach( n => n.remove() );
        (Array
            .from(fieldSets[fieldSets.length - 1].getElementsByClassName('wizard')) as HTMLElement[])
            .filter( n => n instanceof HTMLInputElement)
            .filter( input => input.getAttribute('name') == 'continue')
            .forEach( n => n.remove() );
        (Array
            .from(form.getElementsByClassName('wizard')) as HTMLElement[])
            .filter( n => n instanceof HTMLInputElement)
            .filter( input => input.getAttribute('name') == 'back')
            .forEach( n => n.addEventListener('click', FormWizard.handleBackClick) );
        (Array
            .from(form.getElementsByClassName('wizard')) as HTMLElement[])
            .filter( n => n instanceof HTMLInputElement)
            .filter( input => input.getAttribute('name') == 'continue')
            .forEach( n => n.addEventListener('click', FormWizard.handleForwardClick) );

        const firstFieldError = document.evaluate(".//node()[contains(@class, 'error')]", form, null, XPathResult.FIRST_ORDERED_NODE_TYPE).singleNodeValue;
        if (firstFieldError) {
            const fieldSetToActivate = Array.from(document.evaluate(".//ancestor::fieldset", firstFieldError)).reverse().find( fs => fs.classList.contains('wizard-step'));
            const visibleDisplay = window.getComputedStyle(fieldSets[0]).display;
            fieldSets[0].style.display = "none";
            fieldSetToActivate.style.display = visibleDisplay;
        }

        Array.from(document.evaluate(".//*[self::input]", form))
            .forEach( input => input.addEventListener("keypress", FormWizard.handleEnterSubmission))


        FormWizard.focusFirstInput(fieldSets);
    }
    static handleEnterSubmission(ev: KeyboardEvent): void {
        if (ev.key != "Enter") return;
        ev.stopPropagation();
        ev.preventDefault();
        if (ev.target == null) return;
        Array
            .from(document.evaluate(".//ancestor::fieldset//input[@name = 'continue' or @type = 'submit']", ev.target as Node))
            .forEach( button => button.click() );
    }
    static handleBackClick(ev: MouseEvent): void {
        ev.preventDefault();
        ev.stopPropagation();
        if (ev.target == null) return;
        const currentFieldset = Array.from(document.evaluate(".//ancestor::fieldset", ev.target as Node)).reverse().find( fs => fs.classList.contains('wizard-step'));
        const form = Array.from(document.evaluate(".//ancestor::form", currentFieldset)).reverse()[0];
        const fieldSets = Array
            .from(document.evaluate("./fieldset", form))
            .filter( fs => fs.classList.contains('wizard-step') );
        const visibleDisplay = window.getComputedStyle(currentFieldset).display;
        fieldSets[fieldSets.indexOf(currentFieldset) - 1].style.display = visibleDisplay;
        currentFieldset.style.display = "none";
        FormWizard.focusFirstInput(fieldSets);
    }
    static handleForwardClick(ev: MouseEvent): void {
        ev.preventDefault();
        ev.stopPropagation();
        if (ev.target == null) return;
        const currentFieldset = Array.from(document.evaluate(".//ancestor::fieldset", ev.target as Node)).reverse().find( fs => fs.classList.contains('wizard-step'));
        const form = Array.from(document.evaluate(".//ancestor::form", currentFieldset)).reverse()[0];
        const fieldSets = Array
            .from(document.evaluate("./fieldset", form))
            .filter( fs => fs.classList.contains('wizard-step') );
        const visibleDisplay = window.getComputedStyle(currentFieldset).display;
        fieldSets[fieldSets.indexOf(currentFieldset) + 1].style.display = visibleDisplay;
        currentFieldset.style.display = "none";
        FormWizard.focusFirstInput(fieldSets);
    }
}