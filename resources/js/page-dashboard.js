const DashboardPage = {
    requiresAuth: true,

    render() {
        return `
            <link rel="stylesheet" href="/resources/css/app-ui.css">
            
            <div class="layout">
                ${this.renderSidebar()}
                
                <div class="main-content">
                    ${this.renderNavbar()}
                    
                    <div class="content">
                        <h1 style="margin-bottom: 30px;">Dashboard</h1>
                        
                        <div id="stats-container" class="stats-grid">
                            <div class="loading">
                                <div class="spinner"></div>
                                Loading...
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">Mutasi Terbaru</h2>
                            </div>
                            <div id="recent-transactions" class="table-responsive">
                                <div class="loading">
                                    <div class="spinner"></div>
                                    Loading...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    },

    renderSidebar() {
        return `
            <div class="sidebar">
                <div class="sidebar-header">
                    <div class="sidebar-brand">
                        <div class="sidebar-brand-icon">🏦</div>
                        <span>Bank System</span>
                    </div>
                </div>
                
                <nav class="sidebar-nav">
                    <div class="nav-section">
                        <div class="nav-section-title">Main</div>
                        <a href="?page=dashboard" class="nav-item active">
                            <span class="nav-icon">📊</span>
                            Dashboard
                        </a>
                    </div>
                    
                    <div class="nav-section">
                        <div class="nav-section-title">Management</div>
                        <a href="?page=nasabah" class="nav-item">
                            <span class="nav-icon">👥</span>
                            Nasabah
                        </a>
                        <a href="?page=rekening" class="nav-item">
                            <span class="nav-icon">💳</span>
                            Rekening
                        </a>
                        <a href="?page=mutasi" class="nav-item">
                            <span class="nav-icon">💱</span>
                            Mutasi
                        </a>
                        <a href="?page=statistik" class="nav-item">
                            <span class="nav-icon">📈</span>
                            Statistik
                        </a>
                    </div>
                </nav>
                
                <div class="sidebar-footer">
                    <button class="logout-btn" id="logout-btn">
                        Logout
                    </button>
                </div>
            </div>
        `;
    },

    renderNavbar() {
        const user = auth.getUser();
        const initials = user?.username ? user.username.substring(0, 2).toUpperCase() : 'AD';
        
        return `
            <div class="navbar">
                <div class="navbar-title">Dashboard</div>
                <div class="navbar-user">
                    <div class="user-avatar">${initials}</div>
                    <div class="user-info">
                        <div class="user-name">${user?.username || 'Admin'}</div>
                        <div class="user-role">Administrator</div>
                    </div>
                </div>
            </div>
        `;
    },

    async init() {
        this.setupLogout();
        await this.loadStats();
        await this.loadRecentTransactions();
    },

    setupLogout() {
        document.getElementById('logout-btn').addEventListener('click', () => {
            auth.logout();
            window.location.href = '/app.html?page=login';
        });
    },

    async loadStats() {
        try {
            const data = await api.getStatistik();
            
            let html = '';
            if (data.total_nasabah !== undefined) {
                html += `
                    <div class="stat-card">
                        <div class="stat-label">Total Nasabah</div>
                        <div class="stat-value">${(data.total_nasabah || 0).toLocaleString('id-ID')}</div>
                    </div>
                `;
            }
            if (data.total_rekening !== undefined) {
                html += `
                    <div class="stat-card">
                        <div class="stat-label">Total Rekening</div>
                        <div class="stat-value">${(data.total_rekening || 0).toLocaleString('id-ID')}</div>
                    </div>
                `;
            }
            if (data.total_saldo !== undefined) {
                html += `
                    <div class="stat-card">
                        <div class="stat-label">Total Saldo</div>
                        <div class="stat-value">Rp ${(data.total_saldo || 0).toLocaleString('id-ID')}</div>
                    </div>
                `;
            }
            if (data.total_mutasi !== undefined) {
                html += `
                    <div class="stat-card">
                        <div class="stat-label">Total Mutasi</div>
                        <div class="stat-value">${(data.total_mutasi || 0).toLocaleString('id-ID')}</div>
                    </div>
                `;
            }
            
            document.getElementById('stats-container').innerHTML = html;
        } catch (error) {
            document.getElementById('stats-container').innerHTML = `
                <div class="alert alert-danger">
                    Failed to load statistics: ${error.message}
                </div>
            `;
        }
    },

    async loadRecentTransactions() {
        try {
            const data = await api.getMutasi(1);
            const mutasis = data.data || data || [];

            if (!Array.isArray(mutasis) || mutasis.length === 0) {
                document.getElementById('recent-transactions').innerHTML = `
                    <div class="no-data">
                        <div class="no-data-icon">📭</div>
                        <p>No transactions yet</p>
                    </div>
                `;
                return;
            }

            let html = `
                <table class="table">
                    <thead>
                        <tr>
                            <th>No Rekening</th>
                            <th>Tipe</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            mutasis.slice(0, 10).forEach(mutasi => {
                const tipeClass = mutasi.tipe === 'kredit' ? 'badge-success' : 'badge-danger';
                html += `
                    <tr>
                        <td>${mutasi.no_rekening || '-'}</td>
                        <td><span class="badge ${tipeClass}">${mutasi.tipe || '-'}</span></td>
                        <td>Rp ${(mutasi.jumlah || 0).toLocaleString('id-ID')}</td>
                        <td>${new Date(mutasi.tanggal).toLocaleDateString('id-ID')}</td>
                        <td>${mutasi.keterangan || '-'}</td>
                    </tr>
                `;
            });

            html += `
                    </tbody>
                </table>
            `;

            document.getElementById('recent-transactions').innerHTML = html;
        } catch (error) {
            document.getElementById('recent-transactions').innerHTML = `
                <div class="alert alert-danger">
                    Failed to load transactions: ${error.message}
                </div>
            `;
        }
    }
};

router.register('dashboard', DashboardPage);
