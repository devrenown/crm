// Import jQuery first
import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';
import 'datatables.net-colreorder-bs5';
import 'datatables.net-select-bs5';


import JSZip from 'jszip';
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';

// register fonts
pdfMake.vfs = pdfFonts.pdfMake.vfs;

// make them globally accessible
window.JSZip = JSZip;
window.pdfMake = pdfMake;

// expose jQuery + DataTable globally
window.$ = $;
window.jQuery = $;
window.DataTable = DataTable;

DataTable($);

// Laravel Datatables Vite integration (if using)
import 'laravel-datatables-vite/js/dataTables.buttons.js';
import 'laravel-datatables-vite/js/dataTables.renderers.js';

// Optional: custom defaults
$.extend(true, DataTable.defaults, {
  dom:
    "<'row'<'col-sm-12 mb-4'B>>" +
    "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
});

$.extend(true, DataTable.Buttons.defaults, {
  dom: { buttonLiner: { tag: '' } },
});

$.extend(DataTable.ext.classes, {
  sTable: "dataTable table table-striped table-bordered table-hover",
});

/*
import $ from 'jquery';
import jszip from 'jszip';
import pdfmake from 'pdfmake';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';
import 'datatables.net-colreorder-bs5';
import 'datatables.net-select-bs5';

window.$ = $;
window.jQuery = $;
window.DataTable = DataTable;
window.jszip = jszip;
window.pdfmake = pdfmake;

DataTable($);

import 'laravel-datatables-vite/js/dataTables.buttons.js';
import 'laravel-datatables-vite/js/dataTables.renderers.js';

jQuery.extend(true, DataTable.defaults, {
    dom:
        "<'row'<'col-sm-12 mb-4'B>>" +
        "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
});

jQuery.extend(true, DataTable.Buttons.defaults, {
    dom: {
        buttonLiner: {
            tag: ''
        },
    },
});

jQuery.extend(DataTable.ext.classes, {
    sTable: "dataTable table table-striped table-bordered table-hover",
});
*/