<!-- base -->
<a-form layout="inline">
    <a-form-item label='备注'>
        <a-input v-model.trim="dbInbound.remark"></a-input>
    </a-form-item>
    <a-form-item label='启用'>
        <a-switch v-model="dbInbound.enable"></a-switch>
    </a-form-item>
    <a-form-item label='协议'>
        <a-select v-model="inbound.protocol" style="width: 160px;">
            <a-select-option v-for="p in Protocols" :key="p" :value="p">[[ p ]]</a-select-option>
        </a-select>
    </a-form-item>
    <a-form-item>
        <span slot="label">
            监听 IP
            <a-tooltip>
                <template slot="title">
                    默认留空即可
                </template>
                <a-icon type="question-circle" theme="filled"></a-icon>
            </a-tooltip>
        </span>
        <a-input v-model.trim="inbound.listen"></a-input>
    </a-form-item>
    <a-form-item label="端口">
        <a-input type="number" v-model.number="inbound.port"></a-input>
    </a-form-item>
    <a-form-item>
        <span slot="label">
            总流量(GB)
            <a-tooltip>
                <template slot="title">
                    0 表示不限制
                </template>
                <a-icon type="question-circle" theme="filled"></a-icon>
            </a-tooltip>
        </span>
        <a-input-number v-model="dbInbound.totalGB" :min="0"></a-input-number>
    </a-form-item>
    <a-form-item>
        <span slot="label">
            到期时间
            <a-tooltip>
                <template slot="title">
                    留空则永不到期
                </template>
                <a-icon type="question-circle" theme="filled"></a-icon>
            </a-tooltip>
        </span>
        <a-date-picker :show-time="{ format: 'HH:mm' }" format="YYYY-MM-DD HH:mm"
                       v-model="dbInbound._expiryTime" style="width: 300px;"></a-date-picker>
    </a-form-item>
</a-form>

<!-- vmess settings -->
<template v-if="inbound.protocol === Protocols.VMESS">
    <?php render('xui/form/protocol/vmess.php'); ?>
</template>

<!-- vless settings -->
<template v-if="inbound.protocol === Protocols.VLESS">
    <?php render('xui/form/protocol/vless.php'); ?>
</template>

<!-- trojan settings -->
<template v-if="inbound.protocol === Protocols.TROJAN">
    <?php render('xui/form/protocol/trojan.php'); ?>
</template>

<!-- shadowsocks -->
<template v-if="inbound.protocol === Protocols.SHADOWSOCKS">
    <?php render('xui/form/protocol/shadowsocks.php'); ?>
</template>

<!-- dokodemo-door -->
<template v-if="inbound.protocol === Protocols.DOKODEMO">
    <?php render('xui/form/protocol/dokodemo.php'); ?>
</template>

<!-- socks -->
<template v-if="inbound.protocol === Protocols.SOCKS">
    <?php render('xui/form/protocol/socks.php'); ?>
</template>

<!-- http -->
<template v-if="inbound.protocol === Protocols.HTTP">
    <?php render('xui/form/protocol/http.php'); ?>
</template>

<!-- stream settings -->
<template v-if="inbound.canEnableStream()">
    <?php render('xui/form/stream/stream_settings.php'); ?>
</template>

<!-- tls settings -->
<template v-if="inbound.canEnableTls()">
    <?php render('xui/form/tls_settings.php'); ?>
</template>

<!-- sniffing -->
<template v-if="inbound.canSniffing()">
    <?php render('xui/form/sniffing.php'); ?>
</template>