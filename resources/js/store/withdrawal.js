const state = {
  form: {
    request: false,
    reason: '',
    withdrawal_message: '',  
  },
  // テキストエリアの高さ
  message_height: "auto",
  // チェックマークの表示切り替え
  isActiveCheck: false,
};

const mutations = {
  setRequest(state, bool){
		state.form.request = bool;
	},
	setReason(state, str){
		state.form.reason = str;
	},
  setWithdrawalMessage(state, str){
		state.form.withdrawal_message = str;
	},
  // テキストエリアの高さ
	set_message_height(state, str){
		state.message_height = str;
	},
  // チェックマークの表示切り替え
  setIsActiveCheck(state, bool){
		state.isActiveCheck = bool;
	},
};

export default {
  namespaced: true,
  state,
  mutations,
};