<script>
    Vue.component('inbound-info', {
        delimiters: ['[[', ']]'],
        props: ["dbInbound", "inbound"],
        template: `<?php  render('xui/component/inbound_info_component.php'); ?>`,
    });
</script>