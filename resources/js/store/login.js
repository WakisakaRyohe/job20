const state = {
  email: 'mitsuru28@yahoo.co.jp',
  // 公開時の変更
  // email: '',
};

const mutations = {
	setEmail(state, str){
		state.email = str;
	},
};

export default {
  namespaced: true,
  state,
  mutations,
};