Ext.onReady(function() {
    document.id = document.$id;
    delete document.$id;
});

// Workaround for IE 11 (which is not supported in Ext JS 4.0.7)
Ext.apply(Ext, {
    isIE : true,
    isIE11 : true,
    ieVersion : 11
});