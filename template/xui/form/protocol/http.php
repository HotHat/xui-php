<a-form layout="inline">
    <a-form-item label="用户名">
        <a-input v-model.trim="inbound.settings.accounts[0].user"></a-input>
    </a-form-item>
    <a-form-item label="密码">
        <a-input v-model.trim="inbound.settings.accounts[0].pass"></a-input>
    </a-form-item>
</a-form>