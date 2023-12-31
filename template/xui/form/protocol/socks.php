<a-form layout="inline">
    <a-form-item label="密码认证">
        <a-switch :checked="inbound.settings.auth === 'password'"
                  @change="checked => inbound.settings.auth = checked ? 'password' : 'noauth'"></a-switch>
    </a-form-item>
    <template v-if="inbound.settings.auth === 'password'">
        <a-form-item label="用户名">
            <a-input v-model.trim="inbound.settings.accounts[0].user"></a-input>
        </a-form-item>
        <a-form-item label="密码">
            <a-input v-model.trim="inbound.settings.accounts[0].pass"></a-input>
        </a-form-item>
    </template>
    <a-form-item label="启用 udp">
        <a-switch v-model="inbound.settings.udp"></a-switch>
    </a-form-item>
    <a-form-item v-if="inbound.settings.udp"
                 label="IP">
        <a-input v-model.trim="inbound.settings.ip"></a-input>
    </a-form-item>
</a-form>