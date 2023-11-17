<a-form layout="inline">
    <a-form-item label="id">
        <a-input v-model.trim="inbound.settings.vmesses[0].id"></a-input>
    </a-form-item>
    <a-form-item label="额外 ID">
        <a-input type="number" v-model.number="inbound.settings.vmesses[0].alterId"></a-input>
    </a-form-item>
    <a-form-item label="禁用不安全加密">
        <a-switch v-model.number="inbound.settings.disableInsecure"></a-switch>
    </a-form-item>
</a-form>