// 共有するデータ
const state = {
	modal: {
		apply: { isShow: false, num: 0, id: 0 },
		BookmarkJobs: { isShow: false, num: 0, id: 0 },
		certification: { isShow: false },
		commitment: { isShow: false },
		delete: { isShow: false, num: 0, id: 0},
		flash: { isShow: false, message: null },
		jobDetail: { isShow: false, src: null },
		withdrawal: { isShow: false },
	},
};

// ミューテーション(stateのデータ変更)
const mutations = {
	open(state, {modalName, num, id} ){
		state.modal[modalName].isShow = true;
		state.modal[modalName].num = num;
		state.modal[modalName].id = id;
	},
	close(state, {modalName} ){
		state.modal[modalName].isShow = false;
	},
	setSrc(state, {modalName, src} ){
		state.modal[modalName].src = src;
	},
	setFlash(state, bool){
		state.modal.flash.isShow = bool;
	},
	setMessage(state, str){
		state.modal.flash.message = str;
	},
}

// ゲッター
const getters = {
};

// アクション
const actions = {
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};