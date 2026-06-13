class Router {
    constructor() {
        this.currentPage = null;
        this.pages = {};
    }

    register(name, page) {
        this.pages[name] = page;
    }

    async navigate(pageName, params = {}) {
        if (!this.pages[pageName]) {
            console.error(`Page ${pageName} not found`);
            return;
        }

        const page = this.pages[pageName];
        const root = document.getElementById('root');
        
        if (page.requiresAuth && !auth.isAuthenticated()) {
            this.navigate('login');
            return;
        }

        root.innerHTML = '';
        this.currentPage = page;

        if (page.render) {
            const html = page.render(params);
            root.innerHTML = html;
            if (page.init) {
                page.init(params);
            }
        }
    }

    getCurrentPage() {
        return this.currentPage;
    }

    getPageName(page) {
        for (const [name, p] of Object.entries(this.pages)) {
            if (p === page) return name;
        }
        return null;
    }
}

const router = new Router();

// Initialize router
function initRouter() {
    const params = new URLSearchParams(window.location.search);
    let page = params.get('page') || 'dashboard';

    if (!auth.isAuthenticated()) {
        page = 'login';
    }

    router.navigate(page);
}

window.addEventListener('load', () => {
    setTimeout(initRouter, 100);
});
