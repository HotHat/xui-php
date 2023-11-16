<a-modal id="qrcode-modal" v-model="qrModal.visible" :title="qrModal.title"
         :closable="true" width="300px" :ok-text="qrModal.okText"
         cancel-text='{{ i18n "close" }}' :ok-button-props="{attrs:{id:'qr-modal-ok-btn'}}">
    <canvas id="qrCode" style="width: 100%; height: 100%;"></canvas>
</a-modal>

<script>

    const qrModal = {
        title: '',
        content: '',
        okText: '',
        copyText: '',
        qrcode: null,
        clipboard: null,
        visible: false,
        show: function (title='', content='', okText='{{ i18n "copy" }}', copyText='') {
            this.title = title;
            this.content = content;
            this.okText = okText;
            if (ObjectUtil.isEmpty(copyText)) {
                this.copyText = content;
            } else {
                this.copyText = copyText;
            }
            this.visible = true;
            qrModalApp.$nextTick(() => {
                if (this.clipboard === null) {
                    this.clipboard = new ClipboardJS('#qr-modal-ok-btn', {
                        text: () => this.copyText,
                    });
                    this.clipboard.on('success', () => app.$message.success('{{ i18n "copied" }}'));
                }
                if (this.qrcode === null) {
                    this.qrcode = new QRious({
                        element: document.querySelector('#qrCode'),
                        size: 260,
                        value: content,
                    });
                } else {
                    this.qrcode.value = content;
                }
            });
        },
        close: function () {
            this.visible = false;
        },
    };

    const qrModalApp = new Vue({
        el: '#qrcode-modal',
        data: {
            qrModal: qrModal,
        },
    });

</script>