class AlertElement {
    static #TypeError = Symbol('error');
    static #TypeSuccess = Symbol('success');
    static #ShowDelayed = 5000;
    static #CloseDelayed = 1000;

    #element;

    constructor(text, type) {
        this.#element = $(document.createElement('div'));
        this.#element.attr('role', 'alert');
        
        if(type == AlertElement.TypeError) {
            this.#element.addClass('text-danger');
        } else if(type == AlertElement.TypeSuccess) {
            this.#element.addClass('text-success');
        } else {
            throw new Error('Undefined type of alert message!');
        }
        
        this.#element.text(text);
        this.#element.css('opacity', 0);
        this.#element.addClass('fade show alert border-success bg-dark');
    }

    showAndClose(parent, closeTimeout) {
        parent.append(this.#element);
        this.element.animate({ opacity : 1 });

        this.setElementTimeout((element) => {
            element.animate({opacity: 0});
        }, closeTimeout);

        this.setElementTimeout((element) => {
            element.remove();
        }, closeTimeout + AlertElement.#CloseDelayed);
    }

    setElementTimeout(func, time) {
        setTimeout(() => {
            func(this.element);
        }, time);
    }

    get element() {
        return this.#element;
    }

    static get TypeError() {
        return this.#TypeError;
    }

    static get TypeSuccess() {
        return this.#TypeSuccess;
    }

    static get ShowDelayed() {
        return this.#ShowDelayed;
    }

    static get CloseDelayed() {
        return this.#CloseDelayed;
    }
}