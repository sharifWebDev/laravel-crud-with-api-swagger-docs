// datatables-renderers.js - Same as before
class ModernRenderers {
    static register(tableId, field, renderer) {
        if (!window.dtHelpers) window.dtHelpers = {};
        if (!window.dtHelpers.renderers) window.dtHelpers.renderers = {};
        if (!window.dtHelpers.renderers[tableId]) window.dtHelpers.renderers[tableId] = {};

        window.dtHelpers.renderers[tableId][field] = renderer;
    }

    static applyCustomRenderers(tableId, field, data, row) {
        if (window.dtHelpers &&
            window.dtHelpers.renderers &&
            window.dtHelpers.renderers[tableId] &&
            window.dtHelpers.renderers[tableId][field]) {
            return window.dtHelpers.renderers[tableId][field](data, row);
        }
        return null;
    }
}

window.dtHelpers = {
    renderers: {},

    registerRenderer: (tableId, field, renderFunction) => {
        ModernRenderers.register(tableId, field, renderFunction);
    },

    applyCustomRenderers: (tableId, field, data, row) => {
        return ModernRenderers.applyCustomRenderers(tableId, field, data, row);
    },

    setFilter: (tableId, field, value) => {
        if (window.dtManager) {
            const table = window.dtManager.getTable(tableId);
            if (table) table.setFilter(field, value);
        }
    },

    clearFilters: (tableId) => {
        if (window.dtManager) {
            const table = window.dtManager.getTable(tableId);
            if (table) table.clearFilters();
        }
    },

    refresh: (tableId) => {
        if (window.dtManager) {
            const table = window.dtManager.getTable(tableId);
            if (table) table.refresh();
        }
    },

    exportData: (tableId, format = 'excel') => {
        if (window.dtManager) {
            const table = window.dtManager.getTable(tableId);
            if (table && table.dt) {
                table.showExportMenu();
            }
        }
    },

    showColumns: (tableId) => {
        if (window.dtManager) {
            const table = window.dtManager.getTable(tableId);
            if (table) table.toggleColumnVisibility();
        }
    }
};
