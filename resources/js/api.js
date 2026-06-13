class APIClient {
    constructor(baseURL = '/api') {
        this.baseURL = baseURL;
        this.token = localStorage.getItem('token');
        this.headers = {
            'Content-Type': 'application/json'
        };
        if (this.token) {
            this.headers['Authorization'] = `Bearer ${this.token}`;
        }
    }

    setToken(token) {
        this.token = token;
        if (token) {
            this.headers['Authorization'] = `Bearer ${token}`;
        } else {
            delete this.headers['Authorization'];
        }
    }

    async request(method, endpoint, data = null) {
        const url = `${this.baseURL}${endpoint}`;
        const config = {
            method,
            headers: this.headers
        };

        if (data) {
            config.body = JSON.stringify(data);
        }

        try {
            const response = await fetch(url, config);
            const result = await response.json();

            if (!response.ok) {
                throw {
                    status: response.status,
                    message: result.message || 'An error occurred',
                    errors: result.errors || {}
                };
            }

            return result;
        } catch (error) {
            if (error.status === 401) {
                localStorage.removeItem('token');
                window.location.href = '/app.html?page=login';
            }
            throw error;
        }
    }

    // Auth
    async login(username, password) {
        return this.request('POST', '/login', { username, password });
    }

    // Nasabah
    async getNasabah(page = 1) {
        return this.request('GET', `/nasabah?page=${page}`);
    }

    async getNasabahById(id) {
        return this.request('GET', `/nasabah/${id}`);
    }

    async createNasabah(data) {
        return this.request('POST', '/nasabah', data);
    }

    async updateNasabah(id, data) {
        return this.request('PUT', `/nasabah/${id}`, data);
    }

    async deleteNasabah(id) {
        return this.request('DELETE', `/nasabah/${id}`);
    }

    // Rekening
    async getRekening(page = 1) {
        return this.request('GET', `/rekening?page=${page}`);
    }

    async getRekeningByNoRekening(noRekening) {
        return this.request('GET', `/rekening/${noRekening}`);
    }

    async createRekening(data) {
        return this.request('POST', '/rekening', data);
    }

    async updateRekening(noRekening, data) {
        return this.request('PUT', `/rekening/${noRekening}`, data);
    }

    async deleteRekening(noRekening) {
        return this.request('DELETE', `/rekening/${noRekening}`);
    }

    // Mutasi
    async getMutasi(page = 1) {
        return this.request('GET', `/mutasi?page=${page}`);
    }

    async getMutasiById(id) {
        return this.request('GET', `/mutasi/${id}`);
    }

    async getMutasiByRekening(noRekening) {
        return this.request('GET', `/mutasi/rekening/${noRekening}`);
    }

    async createMutasi(data) {
        return this.request('POST', '/mutasi', data);
    }

    async updateMutasi(id, data) {
        return this.request('PUT', `/mutasi/${id}`, data);
    }

    async deleteMutasi(id) {
        return this.request('DELETE', `/mutasi/${id}`);
    }

    // Statistik
    async getStatistik() {
        return this.request('GET', '/statistik');
    }
}

const api = new APIClient();
