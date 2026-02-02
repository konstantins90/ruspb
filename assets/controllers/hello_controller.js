import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.element.textContent = 'Hello Stimulus! Edit me in assets/controllers/hello_controller.js';
        console.log('STAR');
    }

    search(event) {
        console.log('Test');
        console.log(event.target.value);
    }
}
