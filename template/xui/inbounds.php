<!DOCTYPE html>
<html lang="en">
<?php render("common/head.php"); ?>
<style>
    @media (min-width: 769px) {
        .ant-layout-content {
            margin: 24px 16px;
        }
    }

    .ant-col-sm-24 {
        margin-top: 10px;
    }
</style>
<body>
<a-layout id="app" v-cloak>
    <?php render("xui/common_sider.php"); ?>
    <a-layout id="content-layout">
        <a-layout-content>
            <a-spin :spinning="spinning" :delay="500" tip="loading">
                <transition name="list" appear>
                    <a-tag v-if="false" color="red" style="margin-bottom: 10px">
                        Please go to the panel settings as soon as possible to modify the username and password, otherwise there may be a risk of leaking account information
                    </a-tag>
                </transition>
                <transition name="list" appear>
                    <a-card hoverable style="margin-bottom: 20px;">
                        <a-row>
                            <a-col :xs="24" :sm="24" :lg="12">
                                总上传 / 下载：
                                <a-tag color="green">[[ sizeFormat(total.up) ]] / [[ sizeFormat(total.down) ]]</a-tag>
                            </a-col>
                            <a-col :xs="24" :sm="24" :lg="12">
                                总用量：
                                <a-tag color="green">[[ sizeFormat(total.up + total.down) ]]</a-tag>
                            </a-col>
                            <a-col :xs="24" :sm="24" :lg="12">
                                入站数量：
                                <a-tag color="green">[[ dbInbounds.length ]]</a-tag>
                            </a-col>
                        </a-row>
                    </a-card>
                </transition>
                <transition name="list" appear>
                    <a-card hoverable>
                        <div slot="title">
                            <a-button type="primary" icon="plus" @click="openAddInbound"></a-button>
                        </div>
<!--                        <a-input v-model="searchKey" placeholder="搜索" autofocus style="max-width: 300px"></a-input>-->
                        <a-table :columns="columns" :row-key="dbInbound => dbInbound.id"
                                 :data-source="dbInbounds"
                                 :loading="spinning" :scroll="{ x: 1500 }"
                                 :pagination="false"
                                 style="margin-top: 20px"
                                 @change="() => getDBInbounds()">
                            <template slot="action" slot-scope="text, dbInbound">
                                <a-dropdown :trigger="['click']">
                                    <a @click="e => e.preventDefault()">操作</a>
                                    <a-menu slot="overlay" @click="a => clickAction(a, dbInbound)">
                                        <a-menu-item v-if="dbInbound.hasLink()" key="qrcode">
                                            <a-icon type="qrcode"></a-icon>二维码
                                        </a-menu-item>
                                        <a-menu-item key="edit">
                                            <a-icon type="edit"></a-icon>编辑
                                        </a-menu-item>
                                        <a-menu-item key="resetTraffic">
                                            <a-icon type="retweet"></a-icon>重置流量
                                        </a-menu-item>
                                        <a-menu-item key="delete">
                                            <span style="color: #FF4D4F">
                                                <a-icon type="delete"></a-icon>删除
                                            </span>
                                        </a-menu-item>
                                    </a-menu>
                                </a-dropdown>
                            </template>
                            <template slot="protocol" slot-scope="text, dbInbound">
                                <a-tag color="blue">[[ dbInbound.protocol ]]</a-tag>
                            </template>
                            <template slot="traffic" slot-scope="text, dbInbound">
                                <a-tag color="blue">[[ sizeFormat(dbInbound.up) ]] / [[ sizeFormat(dbInbound.down) ]]</a-tag>
                                <template v-if="dbInbound.total > 0">
                                    <a-tag v-if="dbInbound.up + dbInbound.down < dbInbound.total" color="cyan">[[ sizeFormat(dbInbound.total) ]]</a-tag>
                                    <a-tag v-else color="red">[[ sizeFormat(dbInbound.total) ]]</a-tag>
                                </template>
                                <a-tag v-else color="green">无限制</a-tag>
                            </template>
                            <template slot="settings" slot-scope="text, dbInbound">
                                <a-button type="link" @click="showInfo(dbInbound)">查看</a-button>
                            </template>
                            <template slot="stream" slot-scope="text, dbInbound, index">
                                <template v-if="dbInbound.isVMess || dbInbound.isVLess || dbInbound.isTrojan || dbInbound.isSS">
                                    <a-tag color="green">[[ inbounds[index].stream.network ]]</a-tag>
                                    <a-tag v-if="inbounds[index].stream.isTls" color="blue">tls</a-tag>
                                    <a-tag v-if="inbounds[index].stream.isXTls" color="blue">xtls</a-tag>
                                </template>
                                <template v-else>无</template>
                            </template>
                            <template slot="enable" slot-scope="text, dbInbound">
                                <a-switch v-model="dbInbound.enable" @change="switchEnable(dbInbound)"></a-switch>
                            </template>
                            <template slot="expiryTime" slot-scope="text, dbInbound">
                                <template v-if="dbInbound.expiryTime > 0">
                                    <a-tag v-if="dbInbound.isExpiry" color="red">
                                        [[ DateUtil.formatMillis(dbInbound.expiryTime) ]]
                                    </a-tag>
                                    <a-tag v-else color="blue">
                                        [[ DateUtil.formatMillis(dbInbound.expiryTime) ]]
                                    </a-tag>
                                </template>
                                <a-tag v-else color="green">无限期</a-tag>
                            </template>
                        </a-table>
                    </a-card>
                </transition>
            </a-spin>
        </a-layout-content>
    </a-layout>
</a-layout>
<?php render('common/js.php'); ?>
<script>

    const columns = [{
        title: "操作",
        align: 'center',
        width: 30,
        scopedSlots: { customRender: 'action' },
    }, {
        title: "启用",
        align: 'center',
        width: 40,
        scopedSlots: { customRender: 'enable' },
    }, {
        title: "id",
        align: 'center',
        dataIndex: "id",
        width: 30,
    }, {
        title: "备注",
        align: 'center',
        width: 100,
        dataIndex: "remark",
    }, {
        title: "协议",
        align: 'center',
        width: 60,
        scopedSlots: { customRender: 'protocol' },
    }, {
        title: "端口",
        align: 'center',
        dataIndex: "port",
        width: 60,
    }, {
        title: "流量↑|↓",
        align: 'center',
        width: 150,
        scopedSlots: { customRender: 'traffic' },
    }, {
        title: "详细信息",
        align: 'center',
        width: 40,
        scopedSlots: { customRender: 'settings' },
    }, {
        title: "传输配置",
        align: 'center',
        width: 60,
        scopedSlots: { customRender: 'stream' },
    }, {
        title: "到期时间",
        align: 'center',
        width: 80,
        scopedSlots: { customRender: 'expiryTime' },
    }];

    const app = new Vue({
        delimiters: ['[[', ']]'],
        el: '#app',
        data: {
            siderDrawer,
            spinning: false,
            inbounds: [],
            dbInbounds: [],
            searchKey: '',
        },
        methods: {
            loading(spinning=true) {
                this.spinning = spinning;
            },
            async getDBInbounds() {
                this.loading();
                const msg = await HttpUtil.post(<?php e(url('/xui/inbound/list')) ?>);
                this.loading(false);
                if (!msg.success) {
                    return;
                }
                this.setInbounds(msg.obj);
            },
            setInbounds(dbInbounds) {
                this.inbounds.splice(0);
                this.dbInbounds.splice(0);
                for (const inbound of dbInbounds) {
                    const dbInbound = new DBInbound(inbound);
                    this.inbounds.push(dbInbound.toInbound());
                    this.dbInbounds.push(dbInbound);
                }
            },
            searchInbounds(key) {
                if (ObjectUtil.isEmpty(key)) {
                    this.searchedInbounds = this.dbInbounds.slice();
                } else {
                    this.searchedInbounds.splice(0, this.searchedInbounds.length);
                    this.dbInbounds.forEach(inbound => {
                        if (ObjectUtil.deepSearch(inbound, key)) {
                            this.searchedInbounds.push(inbound);
                        }
                    });
                }
            },
            clickAction(action, dbInbound) {
                switch (action.key) {
                    case "qrcode":
                        this.showQrcode(dbInbound);
                        break;
                    case "edit":
                        this.openEditInbound(dbInbound);
                        break;
                    case "resetTraffic":
                        this.resetTraffic(dbInbound);
                        break;
                    case "delete":
                        this.delInbound(dbInbound);
                        break;
                }
            },
            openAddInbound() {
                inModal.show({
                    title: '添加入站',
                    okText: '添加',
                    confirm: async (inbound, dbInbound) => {
                        inModal.loading();
                        await this.addInbound(inbound, dbInbound);
                        inModal.close();
                    }
                });
            },
            openEditInbound(dbInbound) {
                const inbound = dbInbound.toInbound();
                inModal.show({
                    title: '修改入站',
                    okText: '修改',
                    inbound: inbound,
                    dbInbound: dbInbound,
                    confirm: async (inbound, dbInbound) => {
                        inModal.loading();
                        await this.updateInbound(inbound, dbInbound);
                        inModal.close();
                    }
                });
            },
            async addInbound(inbound, dbInbound) {
                const data = {
                    up: dbInbound.up,
                    down: dbInbound.down,
                    total: dbInbound.total,
                    remark: dbInbound.remark,
                    enable: dbInbound.enable,
                    expiryTime: dbInbound.expiryTime,

                    listen: inbound.listen,
                    port: inbound.port,
                    protocol: inbound.protocol,
                    settings: inbound.settings.toString(),
                    streamSettings: inbound.stream.toString(),
                    sniffing: inbound.canSniffing() ? inbound.sniffing.toString() : '{}',
                };
                await this.submit('/xui/inbound/add', data, inModal);
            },
            async updateInbound(inbound, dbInbound) {
                const data = {
                    up: dbInbound.up,
                    down: dbInbound.down,
                    total: dbInbound.total,
                    remark: dbInbound.remark,
                    enable: dbInbound.enable,
                    expiryTime: dbInbound.expiryTime,

                    listen: inbound.listen,
                    port: inbound.port,
                    protocol: inbound.protocol,
                    settings: inbound.settings.toString(),
                    streamSettings: inbound.stream.toString(),
                    sniffing: inbound.canSniffing() ? inbound.sniffing.toString() : '{}',
                };
                await this.submit(`/xui/inbound/update?id=${dbInbound.id}`, data, inModal);
            },
            resetTraffic(dbInbound) {
                this.$confirm({
                    title: '重置流量',
                    content: '确定要重置流量吗?',
                    okText: '重置',
                    cancelText: '取消',
                    onOk: () => {
                        const inbound = dbInbound.toInbound();
                        dbInbound.up = 0;
                        dbInbound.down = 0;
                        this.updateInbound(inbound, dbInbound);
                    },
                });
            },
            delInbound(dbInbound) {
                this.$confirm({
                    title: '删除入站',
                    content: '确定要删除入站吗?',
                    okText: '删除',
                    cancelText: '取消',
                    onOk: () => this.submit('/xui/inbound/del?id=' + dbInbound.id),
                });
            },
            showQrcode(dbInbound) {
                const link = dbInbound.genLink();
                qrModal.show('二维码', link);
            },
            showInfo(dbInbound) {
                infoModal.show(dbInbound);
            },
            switchEnable(dbInbound) {
                this.submit(<?php e(url('/xui/inbound/update')) ?> + `?id=${dbInbound.id}`, dbInbound);
            },
            async submit(url, data, modal) {
                const msg = await HttpUtil.postWithModal(url, data, modal);
                if (msg.success) {
                    await this.getDBInbounds();
                }
            },
        },
        watch: {
            searchKey(value) {
                this.searchInbounds(value);
            }
        },
        mounted() {
            this.getDBInbounds();
        },
        computed: {
            total() {
                let down = 0, up = 0;
                for (let i = 0; i < this.dbInbounds.length; ++i) {
                    down += this.dbInbounds[i].down;
                    up += this.dbInbounds[i].up;
                }
                return {
                    down: down,
                    up: up,
                };
            }
        },
    });

</script>
<?php render('xui/inbound_modal.php'); ?>
<?php render('common/prompt_modal.php'); ?>
<?php render('common/qrcode_modal.php'); ?>
<?php render('common/text_modal.php'); ?>
<?php render('xui/inbound_info_modal.php'); ?>
</body>
</html>