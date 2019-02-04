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
        console.log('hello app - Dev Server 6666');
    }
}

const app = new App();
Object.freeze(app);

export default app;