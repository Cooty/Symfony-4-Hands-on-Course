import 'bootstrap/dist/css/bootstrap.min.css';
import '@fortawesome/fontawesome-free/css/all.min.css';
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

        try {
            this.init();
        } catch (e) {
            console.warn(e);
        }

        return App.instance;
    }

    init() {
        if(window._config.isLoggedIn) {
            import('./modules/fetch-notification-count')
                .then(({default: fetchNotificationCount})=> {
                    fetchNotificationCount();
                })
                .catch((reason)=> {
                    throw Error(reason);
                })
        }
    }
}

// Need some additional settings to work with Vagrant file-syncing
// https://github.com/symfony/webpack-encore/issues/191
const app = new App();
Object.freeze(app);

export default app;