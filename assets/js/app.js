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