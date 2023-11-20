<div>
    <p>协议: <a-tag color="green">[[ dbInbound.protocol ]]</a-tag></p>
    <p>地址: <a-tag color="blue">[[ dbInbound.address ]]</a-tag></p>
    <p>端口: <a-tag color="green">[[ dbInbound.port ]]</a-tag></p>

    <template v-if="dbInbound.isVMess">
        <p>uuid: <a-tag color="green">[[ inbound.uuid ]]</a-tag></p>
        <p>alterId: <a-tag color="green">[[ inbound.alterId ]]</a-tag></p>
    </template>

    <template v-if="dbInbound.isVLess">
        <p>uuid: <a-tag color="green">[[ inbound.uuid ]]</a-tag></p>
        <p v-if="inbound.isXTls">flow: <a-tag color="green">[[ inbound.flow ]]</a-tag></p>
    </template>

    <template v-if="dbInbound.isTrojan">
        <p>密码: <a-tag color="green">[[ inbound.password ]]</a-tag></p>
    </template>

    <template v-if="dbInbound.isSS">
        <p>加密: <a-tag color="green">[[ inbound.method ]]</a-tag></p>
        <p>密码: <a-tag color="green">[[ inbound.password ]]</a-tag></p>
    </template>

    <template v-if="dbInbound.isSocks">
        <p>用户名: <a-tag color="green">[[ inbound.username ]]</a-tag></p>
        <p>密码: <a-tag color="green">[[ inbound.password ]]</a-tag></p>
    </template>

    <template v-if="dbInbound.isHTTP">
        <p>用户名: <a-tag color="green">[[ inbound.username ]]</a-tag></p>
        <p>密码: <a-tag color="green">[[ inbound.password ]]</a-tag></p>
    </template>

    <template v-if="dbInbound.isVMess || dbInbound.isVLess || dbInbound.isTrojan || dbInbound.isSS">
        <?php render('xui/component/inbound_info_stream.php'); ?>
    </template>
</div>