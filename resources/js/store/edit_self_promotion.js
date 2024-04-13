import router from '../router';
import store from './index';

const state = {
	// フォームデータ
	self_promotion: {
		self_promotion: '',
		motivation: '',
	},
	// テキストエリアの高さ
	self_promotion_height: "auto",
	motivation_height: "auto",
	// ボタンクリック判定
	isClicked: false,
	// 処理中の画面表示
	isLoading: true,
};

const mutations = {
	set_data(state, data){
		state.self_promotion = data;
	},
	set_self_promotion(state, str){
		state.self_promotion.self_promotion = str;
	},
	set_motivation(state, str){
		state.self_promotion.motivation = str;
	},
	// テキストエリアの高さ
	set_self_promotion_height(state, str){
		state.self_promotion_height = str;
	},
	set_motivation_height(state, str){
		state.motivation_height = str;
	},
	// ボタンクリック判定
	set_isClicked(state, bool){
		state.isClicked = bool;
	},
	// 処理中の画面表示
	setIsLoading(state, bool){
		state.isLoading = bool;
	},
	// エラー
	setErrors(state, data){
		state.errors = data;
	},
};

const actions = {
	// 自己PR更新処理
	updateSelfPromotion: async function (context){
		// 処理中の画面表示
		store.commit('EditSelfPromotion/setIsLoading', true);

		// ボタン連打を無効化
		store.commit('EditSelfPromotion/set_isClicked', true);

		// 送信データを用意
		let params = {
			self_promotion: context.state.self_promotion.self_promotion,
			motivation: context.state.self_promotion.motivation,
			// testFlg: false.,
		};

		// エラー配列初期化
		store.commit('EditSelfPromotion/setErrors', {});

		// axiosで使うthisを格納
		const self = this;

		await axios.post('/job20/web/self_promotion', params)
		.then(response => {
			if(response.data.successFlg) {
				if(response.data.new_self_promotion) {
					if(response.data.editFlg){
						store.commit('Modal/setMessage', '自己PRを更新しました。');				
					}else{
						store.commit('Modal/setMessage', '自己PRを登録しました。');
					}

					// フラッシュメッセージ表示
					store.commit('Modal/setFlash', true);
					router.push({ name: 'web_resume', params: { propNum: 4 } });

					// データ削除
					store.commit('EditSelfPromotion/set_self_promotion', '');
					store.commit('EditSelfPromotion/set_motivation', '');
				}
			}else{
				router.push({ name: 'error' });
			}
		}).catch(error => {
			let errors = {};
			// 返却データを配列化
			for(let key in error.response.data.errors) {
				errors[key] = error.response.data.errors[key];
			}
		
			// これがないと、パスワード入力でリアルタイムバリデーションが作動しない
			// Laravelのバリデーションでエラーメッセージを格納した後、他のエラー配列が定義されていないため
			// 自己PR
			if(errors.self_promotion === undefined){
				errors.self_promotion = [];
			}
			// 志望動機
			if(errors.motivation === undefined){
				errors.motivation = [];
			}

			// stateに代入
			store.commit('EditSelfPromotion/setErrors', errors);
		
			// フォーム編集フラグは有効のままにする
			store.commit('FormChanged/set_formChanged', true);
		
			// トップにスクロール
			window.scrollTo({top: 0, behavior: 'smooth'})
		});
		
		// ボタンを初期状態に戻す
		store.commit('EditSelfPromotion/set_isClicked', false);
		
		// 処理中の画面を非表示
		store.commit('EditSelfPromotion/setIsLoading', false);
	},
};

export default {
  namespaced: true,
  state,
  mutations,
  actions
};