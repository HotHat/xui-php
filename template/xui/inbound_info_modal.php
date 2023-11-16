<a-modal id="inbound-info-modal" v-model="infoModal.visible" title="详细信息" @ok="infoModal.ok"
         :closable="true" :mask-closable="true"
         ok-text="复制链接" cancel-text='{{ i18n "close" }}' :ok-button-props="infoModal.okBtnPros">
    <inbound-info :db-inbound="dbInbound" :inbound="inbound"></inbound-info>
</a-modal>
<script>

    const infoModal = {
        visible: false,
        inbound: new Inbound(),
        dbInbound: new DBInbound(),
        clipboard: null,
        okBtnPros: {
            attrs: {
                id: "inbound-info-modal-ok-btn",
                style: "",
            },
        },
        show(dbInbound) {
            this.inbound = dbInbound.toInbound();
            this.dbInbound = new DBInbound(dbInbound);
            this.visible = true;

            if (dbInbound.hasLink()) {
                this.okBtnPros.attrs.style = "";
            } else {
                this.okBtnPros.attrs.style = "display: none";
            }

            if (this.clipboard == null) {
                infoModalApp.$nextTick(() => {
                    this.clipboard = new ClipboardJS(`#${this.okBtnPros.attrs.id}`, {
                        text: () => this.dbInbound.genLink(),
                    });
                    this.clipboard.on('success', () => app.$message.success('复制成功'));
                });
            }
        },
        close() {
            infoModal.visible = false;
        },
    };

    const infoModalApp = new Vue({
        delimiters: ['[[', ']]'],
        el: '#inbound-info-modal',
        data: {
            infoModal,
            get dbInbound() {
                return this.infoModal.dbInbound;
            },
            get inbound() {
                return this.infoModal.inbound;
            }
        },
    });

</script>