class AuthManager {
    constructor() {
        this.token = localStorage.getItem('token');
        this.user = JSON.parse(localStorage.getItem('user') || 'null');
    }

    isAuthenticated() {
        return !!this.token;
    }

    async login(username, password) {
        try {
            const response = await api.login(username, password);
            this.setAuth(response.token, response.user || { username });
            return true;
        } catch (error) {
            throw error;
        }
    }

    setAuth(token, user = {}) {
        this.token = token;
        this.user = user;
        localStorage.setItem('token', token);
        localStorage.setItem('user', JSON.stringify(user));
        api.setToken(token);
    }

    logout() {
        this.token = null;
        this.user = null;
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        api.setToken(null);
    }

    getUser() {
        return this.user;
    }

    getToken() {
        return this.token;
    }
}

const auth = new AuthManager();
