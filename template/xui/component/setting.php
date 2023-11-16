<script>
    Vue.component('setting-list-item', {
        props: ["type", "title", "desc", "value"],
        template: `<?php render('xui/component/list_item.php') ?>`,
    });
</script>