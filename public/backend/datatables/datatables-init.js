// datatables-init.js - Safe initialization
document.addEventListener('DOMContentLoaded', function() {
    console.log('DataTables initialization started...');

    // Wait a bit for scripts to load completely
    setTimeout(() => {
        if (window.dtManager && window.dtManager.initialized) {
            // console.log('✅ Modern DataTables initialized successfully');

            // Re-initialize tables in case any were missed
            window.dtManager.initTables();
        } else {
            // console.warn('⚠️ DataTables Manager not fully initialized, retrying...');
            if (!window.dtManager) {
                window.dtManager = new DataTablesManager();
            }
        }
    }, 1000);
});

// Safe table creator
window.createModernTable = (config) => {
    setTimeout(() => {
        if (window.dtManager && window.dtManager.initialized) {
            // Implementation here
        } else {
            // console.warn('⚠️ DataTables Manager not fully initialized, retrying...');
            if (!window.dtManager) {
                window.dtManager = new DataTablesManager();
            }
        }
    }, 500);
};
