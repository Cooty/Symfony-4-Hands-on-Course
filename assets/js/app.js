import 'bootstrap/dist/css/bootstrap.min.css';
import  '../scss/app.scss';

import 'jquery/dist/jquery.slim.js';
import 'popper.js/dist/popper.js';
import 'bootstrap/dist/js/bootstrap.min.js';
import 'holderjs/holder.min.js';

class App {
    constructor() {
        if(!App.instance) {
            App.instance = this;
        }

        this.init();

        return App.instance;
    }

    init() {
        console.log('App initialized - change v7');
    }
}

// Need some additional settings to work with Vagrant file-syncing
// https://github.com/symfony/webpack-encore/issues/191
const app = new App();
Object.freeze(app);

export default app;