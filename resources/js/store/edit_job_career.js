import router from '../router';
import store from './index';

const state = {
	// フォームデータ
	job_career: {
		// 社名
		job_careers_company_name: '',
		// 在籍期間
		year_of_joining: '',
		month_of_joining: '',
		year_of_retirement: '',
		month_of_retirement: '',
		// 雇用形態
		employment_status: '',
		// 業種
		job_careers_industry: '',
		// 従業員数
		number_of_employees: '',
		// 部署/役職
		department_or_post: '',
		// 職務内容
		job_details: '',
	},
	// テキストエリアの高さ
	textarea_height: "auto",
	// ボタンクリック判定
	isClicked: false,
	// 処理中の画面表示
	isLoading: true,
};

const mutations = {
	set_job_career(state, data){
		state.job_career = data;
	},
	// 社名
	set_job_careers_company_name(state, str){
		state.job_career.job_careers_company_name = str;
	},
	// 在籍期間
	set_year_of_joining(state, str){
		state.job_career.year_of_joining = str;
	},
	set_month_of_joining(state, str){
		state.job_career.month_of_joining = str;
	},
	set_year_of_retirement(state, str){
		state.job_career.year_of_retirement = str;
	},
	set_month_of_retirement(state, str){
		state.job_career.month_of_retirement = str;
	},
	// 雇用形態
	set_employment_status(state, str){
		state.job_career.employment_status = str;
	},
	// 業種
	set_job_careers_industry(state, str){
		state.job_career.job_careers_industry = str;
	},
	// 従業員数
	set_number_of_employees(state, str){
		state.job_career.number_of_employees = str;
	},
	// 部署/役職
	set_department_or_post(state, str){
		state.job_career.department_or_post = str;
	},
	// 職務内容
	set_job_details(state, str){
		state.job_career.job_details = str;
	},

	// テキストエリアの高さ
	set_textarea_height(state, str){
		state.textarea_height = str;
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
	// 職務経歴の更新処理
	updateJobCareer: async function (context, {id: id}){
		// 処理中の画面表示
		store.commit('EditJobCareer/setIsLoading', true);

		// ボタン連打を無効化
		store.commit('EditJobCareer/set_isClicked', true);

		// エラー配列初期化
		store.commit('EditJobCareer/setErrors', {});
		
		// axiosで使うthisを格納
		const self = this;

		// 送信データを用意
		let params = {
			job_careers_company_name: context.state.job_career.job_careers_company_name,
			year_of_joining: context.state.job_career.year_of_joining,
			month_of_joining: context.state.job_career.month_of_joining,
			year_of_retirement: context.state.job_career.year_of_retirement,
			month_of_retirement: context.state.job_career.month_of_retirement,
			employment_status: context.state.job_career.employment_status,
			job_careers_industry: context.state.job_career.job_careers_industry,
			number_of_employees: context.state.job_career.number_of_employees,
			department_or_post: context.state.job_career.department_or_post,
			job_details: context.state.job_career.job_details,
			// testFlg: false.,
		};

		await axios.post('/job20/web/job_career/' + id, params)
		.then(response => {
			if(response.data.successFlg) {
				if(response.data.job_career) {
					if(response.data.editFlg){
						store.commit('Modal/setMessage', '職務経歴を更新しました。');
					}else{
						store.commit('Modal/setMessage', '職務経歴を登録しました。');
					}

					// フラッシュメッセージ表示
					store.commit('Modal/setFlash', true);
					// WEB履歴書ページへ遷移
					router.push({ name: 'web_resume', params: { propNum: 2 } })

					// データ削除
					store.commit('EditJobCareer/set_job_careers_company_name', '');
					store.commit('EditJobCareer/set_year_of_joining', '');
					store.commit('EditJobCareer/set_month_of_joining', '');
					store.commit('EditJobCareer/set_year_of_retirement', '');
					store.commit('EditJobCareer/set_month_of_retirement', '');
					store.commit('EditJobCareer/set_employment_status', '');
					store.commit('EditJobCareer/set_job_careers_industry', '');
					store.commit('EditJobCareer/set_number_of_employees', '');
					store.commit('EditJobCareer/set_department_or_post', '');
					store.commit('EditJobCareer/set_job_details', '');					
					store.commit('EditJobCareer/set_textarea_height', "auto");				
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
			// 社名
			if(errors.job_careers_company_name === undefined){
				errors.job_careers_company_name = [];
			}
			// 在籍期間		
			if(errors.year_of_joining === undefined){
				errors.year_of_joining = [];
			}
			if(errors.month_of_joining === undefined){
				errors.month_of_joining = [];
			}
			if(errors.year_of_retirement === undefined){
				errors.year_of_retirement = [];
			}
			if(errors.month_of_retirement === undefined){
				errors.month_of_retirement = [];
			}
			// 雇用形態
			if(errors.employment_status === undefined){
				errors.employment_status = [];
			}
			// 業種
			if(errors.job_careers_industry === undefined){
				errors.job_careers_industry = [];
			}
			// 従業員数
			if(errors.number_of_employees === undefined){
				errors.number_of_employees = [];
			}
			// 部署/役職
			if(errors.department_or_post === undefined){
				errors.department_or_post = [];
			}
			// 職務内容
			if(errors.job_details === undefined){
				errors.job_details = [];
			}

			// stateに代入
			store.commit('EditJobCareer/setErrors', errors);
		
			// フォーム編集フラグは有効のままにする
			store.commit('FormChanged/set_formChanged', true);
		
			// トップにスクロール
			window.scrollTo({top: 0, behavior: 'smooth'})
		});
		
		// ボタンを初期状態に戻す
		store.commit('EditJobCareer/set_isClicked', false);
		
		// 処理中の画面を非表示
		store.commit('EditJobCareer/setIsLoading', false);
	},
};

export default {
	namespaced: true,
	state,
	mutations,
	actions
};
