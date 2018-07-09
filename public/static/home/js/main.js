    const rootPath = document.getElementById("rootPath").value;
    Vue.prototype.$http = axios;
    new Vue({
        el: '#app',
        data: {
            Home: true,
            IndexPage: '1',
            AdminPage: '1',
            navLists: [],
            artLists: [],
            artDetail: [],
            usrLists: [],
            UserName: '',
            Password: '',
            RePassword: '',
            loginStatus: '',
            userSession: '',
            statusSession: ''
        },
        created() {
            console.log(this.IndexPage);
            var getNav = rootPath + '/getNav';
            this.$http.get(`${getNav}`)
                    .then(response => {
                        console.log(response.data);
                        for (data of response.data) {
                            this.navLists.push(data);
                        }
                    })
                    .catch(err => console.log(err));
            this.getUserSession();
        },
        methods: {
            getUserSession() {
                var getUserSession = rootPath + '/getUserSession';
                this.$http.get(`${getUserSession}`)
                        .then(response => {
                            console.log(response.data);
                            this.userSession = response.data;
                        })
                        .catch(err => console.log(err))
            },
            getStatusSession() {
                var getStatusSession = rootPath + '/getStatusSession';
                this.$http.get(`${getStatusSession}`)
                        .then(response => {
                            this.statusSession = response.data;
                        })
                        .catch(err => console.log(err))
            },
            goIndex() {
                if (!this.Home) this.Home = !this.Home;
                this.IndexPage = '1';
            },
            getArts(id) {
                var getArts = rootPath + '/getArts';
                var getArtsNav = rootPath + './getArtsNav';
                newLists = [];
                console.log(this.artLists);
                console.log(id);
                var params = isNaN(id) ? `${getArts}` : `${getArtsNav}/${id}`;
                console.log(params);
                this.$http.get(`${params}`)
                        .then(response => {
                            if (response.data.length == 0) {
                                console.log("get nothing");
                            } else {
                                for (data of response.data) {
                                    newLists.push(data);
                                }
                            }
                            if (this.IndexPage != '2') this.IndexPage = '2';  // 数据驱动更新视图
                            this.artLists = newLists;
                            console.log(this.IndexPage);
                            console.log(this.artLists);
                        })
                        .catch(err => console.log(err))
            },
            getDetail(id) {
                var getDetail = rootPath + '/getDetail';
                console.log(this.IndexPage);
                this.$http.get(`${getDetail}/${id}`)
                        .then(response => {
                            console.log(response.data)
                            this.artDetail = response.data;
                            this.IndexPage = '3';
                        })
            },
            goLogin() {
                this.IndexPage = '4';
            },
            goSign() {
                console.log("!23");
                this.IndexPage = '5';
            },
            login(scene) {
                console.log(this.UserName);
                var loginCheck = rootPath + '/loginCheck';
                this.$http.post(`${loginCheck}`, {
                    "user": this.UserName,
                    "pass": this.Password,
                    "repass": this.RePassword,
                    "scene": scene
                })
                .then(response => {
                    console.log(response.data);
                    this.loginStatus = response.data.info
                    if (response.data.status === 200) {
                        this.IndexPage = '1';
                        this.getUserSession();
                    }
                })
                .catch(err => console.log(err));
            },
            logout() {
                var logout = rootPath + '/logout';
                this.$http.get(`${logout}`)
                        .then(response => {
                            console.log(response.data);
                            if (response.data.status === 200) {
                                console.log("退出成功");
                                if (!this.Home) this.Home = !this.Home;
                                if (this.IndexPage != '1') this.IndexPage = '1';
                                console.log(this.Home);
                                this.getUserSession();
                            }
                        })
                        .catch(err => console.log(err))
            },
            goAdmin() {
                if (this.Home) this.Home = !this.Home;
                if (this.AdminPage != '1') this.AdminPage = '1';
                this.getArts();
                this.getStatusSession();
            },
            goUser() {
                if (this.AdminPage != '4') this.AdminPage = '4';
                this.getUsers();
            },
            getUsers() {
                var getUsers = rootPath + '/getUsers';
                this.$http.get(`${getUsers}`)
                        .then(response => {
                            console.log(response.data);
                            this.usrLists = response;
                        })
                        .catch(err => console.log(err))
            },
            atcEdit(id) {
                if (id) {
                    var params = rootPath + `/getAtc/${id}`;
                } else {
                    if (this.AdminPage != '3') {
                        this.AdminPage = '3';
                        return;
                    }
                }
                this.$http.get(`${params}`)
                        .then(response => {
                            this.artDetail = response.data;
                            console.log(this.artDetail);
                            if (this.AdminPage != '3') this.AdminPage = '3';
                        })
                        .catch(err => console.log(err))
            },
            atcAdd(id) {
                if (id) {
                    var params = rootPath + `/addAtc/${id}`;
                    this.$http.get(`${params}`)
                            .then(response => {
                                console.log("添加成功");
                                this.goAdmin();
                            })
                            .catch(err => console.log(err))
                }
            },
            atcDel(id) {
                if (id) {
                    var params = rootPath + '/atcDel';
                    this.$http.get(`${params}/${id}`)
                            .then(response => {
                                console.log(response.data);
                                this.goAdmin();
                                if (response.data.status == 200) {
                                    console.log("删除成功");
                                    this.goAdmin();
                                } else {
                                    console.log("删除失败");
                                }
                            })
                            .catch(err => console.log(err))
                }
            }
        },
        components: {
            'sign-button': {
                template: '<button class="btn dark-btn" @click="goSign">Sign Up</button>',
                methods: {
                    goSign() {
                        this.$emit('clicked');
                    }
                }
            }
        }
    });