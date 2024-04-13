import router from '../router';
import store from './index';

const state = {
	// フォームデータ
	skill: {
		// 保有資格
		certifications: [],
		// 英語スキル
		reading_english_level: '',
		speaking_english_level: '',
		writing_english_level: '',
		toeic_score: '',
		toefl_score: '',
		// その他の語学スキル
		others_language: '',
		input_others_language: '',
		reading_others_language_level: '',
		speaking_others_language_level: '',
		writing_others_language_level: '',
	},
	// 保有資格関係
	// チェックしたアイテムが所属するカテゴリ
	categories: [],
	// 選択中のアイテムのカテゴリ
	categoryNum: 1,
	// インクリメンタルサーチのアイテムリスト
	suggestList: [],
	// チェックマークをつけるアイテムの配列
	activeCheckArray: [],
	// タブ切り替え
	isActiveTab: 1,
	// アコーディオン切り替え
	isShowTable: true,
	// フリーワード
	freeword: '',
	// フリーワード検索で見つからない場合の、入力画面表示フラグ
	isNoIndex: false,
	// フリーワード検索で見つからない場合の、入力したフリーワード
	inputFreeword: '',

	// その他言語入力フォームの表示フラグ
	isSelectOthersLanguage: false,
	// ボタンクリック判定
	isClicked: false,
	// 処理中の画面表示
	isLoading: true,
};

const mutations = {
	set_skill(state, data){
		state.skill = data;
	},
	// 保有資格
	set_certifications(state, str){
		state.skill.certifications = str;
	},
	set_certifications_all(state, array){
		state.skill.certifications = array;
	},
	// 英語スキル
	set_reading_english_level(state, str){
		state.skill.reading_english_level = str;
	},
	set_speaking_english_level(state, str){
		state.skill.speaking_english_level = str;
	},
	set_writing_english_level(state, str){
		state.skill.writing_english_level = str;
	},
	set_toeic_score(state, num){
		state.skill.toeic_score = num;
	},
	set_toefl_score(state, num){
		state.skill.toefl_score = num;
	},
	// その他の語学スキル
	set_others_language(state, str){
		state.skill.others_language = str;
	},
	set_input_others_language(state, str){
		state.skill.input_others_language = str;
	},
	set_reading_others_language_level(state, str){
		state.skill.reading_others_language_level = str;
	},
	set_speaking_others_language_level(state, str){
		state.skill.speaking_others_language_level = str;
	},
	set_writing_others_language_level(state, str){
		state.skill.writing_others_language_level = str;
	},
	
	// 保有資格関係
 	setInputFreewordToArray(state, str){
		state.skill.certifications.push(str);
	},
	setCategories(state, array){
		state.categories = array;
	},
  	setCategoryNum(state, num){
		state.categoryNum = num;
	},
  	setSuggestList(state, array){
		state.suggestList = array;
	},
	setFreeword(state, str){
		state.freeword = str;
	},
	setActiveTab(state, num){
		state.isActiveTab = num;
	},
	setNoIndex(state, bool){
		state.isNoIndex = bool;
	},
	setInputFreeword(state, str){
		state.inputFreeword = str;
	},
  	setActiveCheckName(state, str){
		state.activeCheckArray.push(str);
	},
  	setActiveCheckArray(state, array){
		state.activeCheckArray = array;
	},
	setIsShowTable(state, bool){
		state.isShowTable = bool;
	},

	// その他言語入力フォームの表示フラグ
	set_isSelectOthersLanguage(state, bool){
		state.isSelectOthersLanguage = bool;
	},
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
	// 資格・更新処理
	updateSkill: async function (context){
		// 処理中の画面表示
		store.commit('EditSkill/setIsLoading', true);

		// ボタン連打を無効化
		store.commit('EditSkill/set_isClicked', true);

		// 送信データを用意
		let params = {
			certifications: context.state.skill.certifications,
			reading_english_level: context.state.skill.reading_english_level,
			speaking_english_level: context.state.skill.speaking_english_level,
			writing_english_level: context.state.skill.writing_english_level,
			toeic_score: context.state.skill.toeic_score,
			toefl_score: context.state.skill.toefl_score,
			others_language: context.state.skill.others_language,
			input_others_language: context.state.skill.input_others_language,
			reading_others_language_level: context.state.skill.reading_others_language_level,
			speaking_others_language_level: context.state.skill.speaking_others_language_level,
			writing_others_language_level: context.state.skill.writing_others_language_level,
			// testFlg: false.,
		};

		// エラー配列初期化
		store.commit('EditSkill/setErrors', {});

		// axiosで使うthisを格納
		const self = this;

		await axios.post('/job20/web/skill', params)
		.then(response => {
			if(response.data.successFlg) {
				if(response.data.newSkill) {
					if(response.data.editFlg){
						store.commit('Modal/setMessage', '資格・スキルを更新しました。');
					}else{
						store.commit('Modal/setMessage', '資格・スキルを登録しました。');
					}
					
					// フラッシュメッセージ表示
					store.commit('Modal/setFlash', true);
					// WEB履歴書ページへ遷移					
					router.push({ name: 'web_resume', params: { propNum: 3 } });

					// データ削除
					store.commit('EditSkill/set_skill', {});
					store.commit('EditSkill/setCategories', []);
					store.commit('EditSkill/setCategoryNum', 1);
					store.commit('EditSkill/setSuggestList', []);
					store.commit('EditSkill/setActiveCheckArray', []);
					store.commit('EditSkill/setActiveTab', 1);
					store.commit('EditSkill/setFreeword', '');
					store.commit('EditSkill/setNoIndex', false);
					store.commit('EditSkill/setInputFreeword', '');
					// 資格の配列を定義しないとエラーになる
					store.commit('EditSkill/set_certifications_all', []);
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
			// 保有資格
			if(errors.certifications === undefined){
				errors.certifications = [];
			}
			// 英語スキル
			if(errors.reading_english_level === undefined){
				errors.reading_english_level = [];
			}
			if(errors.speaking_english_level === undefined){
				errors.speaking_english_level = [];
			}
			if(errors.writing_english_level === undefined){
				errors.writing_english_level = [];
			}
			if(errors.toeic_score === undefined){
				errors.toeic_score = [];
			}
			if(errors.toefl_score === undefined){
				errors.toefl_score = [];
			}
			// その他の語学スキル
			if(errors.others_language === undefined){
				errors.others_language = [];
			}
			if(errors.input_others_language === undefined){
				errors.input_others_language = [];
			}
			if(errors.reading_others_language_level === undefined){
				errors.reading_others_language_level = [];
			}
			if(errors.speaking_others_language_level === undefined){
				errors.speaking_others_language_level = [];
			}
			if(errors.writing_others_language_level === undefined){
				errors.writing_others_language_level = [];
			}
			
			// stateに代入
			store.commit('EditSkill/setErrors', errors);
		
			// フォーム編集フラグは有効のままにする
			store.commit('FormChanged/set_formChanged', true);
		
			// トップにスクロール
			window.scrollTo({top: 0, behavior: 'smooth'})
		});
		
		// ボタンを初期状態に戻す
		store.commit('EditSkill/set_isClicked', false);
		
		// 処理中の画面を非表示
		store.commit('EditSkill/setIsLoading', false);
	},
};

export default {
  namespaced: true,
  state,
  mutations,
  actions
};