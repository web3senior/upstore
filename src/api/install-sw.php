<!doctype html>
<title>installing service worker</title>
<script type='text/javascript'>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('<?=URL?>public/js/pwa/sw.js');
    }
    ;
</script>