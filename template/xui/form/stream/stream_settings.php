<!-- select stream network -->
<a-form layout="inline">
    <a-form-item label="传输">
        <a-select v-model="inbound.stream.network" @change="streamNetworkChange">
            <a-select-option value="tcp">tcp</a-select-option>
            <a-select-option value="kcp">kcp</a-select-option>
            <a-select-option value="ws">ws</a-select-option>
            <a-select-option value="http">http</a-select-option>
            <a-select-option value="quic">quic</a-select-option>
            <a-select-option value="grpc">grpc</a-select-option>
        </a-select>
    </a-form-item>
</a-form>

<!-- tcp -->
<template v-if="inbound.stream.network === 'tcp'">
    <?php render('xui/form/stream/stream_tcp.php'); ?>
</template>

<!-- kcp -->
<template v-if="inbound.stream.network === 'kcp'">
    <?php render('xui/form/stream/stream_kcp.php'); ?>
</template>

<!-- ws -->
<template v-if="inbound.stream.network === 'ws'">
    <?php render('xui/form/stream/stream_ws.php'); ?>
</template>

<!-- http -->
<template v-if="inbound.stream.network === 'http'">
    <?php render('xui/form/stream/stream_http.php'); ?>
</template>

<!-- quic -->
<template v-if="inbound.stream.network === 'quic'">
    <?php render('xui/form/stream/stream_quic.php'); ?>
</template>

<!-- grpc -->
<template v-if="inbound.stream.network === 'grpc'">
    <?php render('xui/form/stream/stream_grpc.php'); ?>
</template>