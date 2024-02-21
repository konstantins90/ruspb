import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        console.log('STAR');
    }

    search(event) {
        console.log('Test');
        console.log(event.target.value);
    }
}
