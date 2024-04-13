import router from '../router';
import store from './index';

const state = {
	profile: {
		// 名前
		last_name: '',
		last_name_kana: '',
		first_name: '',
		first_name_kana: '',
		// 生年月日
		year_of_birth: '',
		month_of_birth: '',
		date_of_birth: '',
		age: 'ー',
		// 性別
		sex: '',
		// 住所
		zip: '',
		prefecture: '',
		municipalities: '',
		other_address: '',
		// 電話番号
		tel: '',
		// 学歴
		school_type: '',
		school_name: '',
		faculty_and_department: '',
		literature_or_science: '',
		enrollment_year: '',
		graduation_year: '',
		graduation_type: '',
		// その他
		job_change_experience: '',
		employment_status: '',
		latest_annual_income: '',
		// 画像
		id_photo: '',
	},
	imageData: '', // リアルタイムプレビューで利用
	file: '', // 画像情報
	borderChange: false,
	// ボタンクリック判定
	isClicked: false,
	// 処理中の画面表示
	isLoading: true,
	// エラーオブジェクト
	errors: {
		zip: [],
	},
};

const mutations = {
	setProfile(state, data){
		state.profile = data;
	},
	// 名前
	setLastName(state, str){
		state.profile.last_name = str;
	},
	setLastNameKana(state, str){
		state.profile.last_name_kana = str;
	},
	setFirstName(state, str){
		state.profile.first_name = str;
	},
	setFirstNameKana(state, str){
		state.profile.first_name_kana = str;
	},
	// 生年月日
	setYearOfBirth(state, num){
		state.profile.year_of_birth = num;
	},
	setMonthOfBirth(state, num){
		state.profile.month_of_birth = num;
	},
	setDateOfBirth(state, num){
		state.profile.date_of_birth = num;
	},
	setAge(state, num){
		state.profile.age = num;
	},
	// 性別
	setSex(state, str){
		state.profile.sex = str;
	},
	// 住所  
	setZip(state, str){
		state.profile.zip = str;
	},
	setPrefecture(state, str){
		state.profile.prefecture = str;
	},
	setMunicipalities(state, str){
		state.profile.municipalities = str;
	},
	setOtherAddress(state, str){
		state.profile.other_address = str;
	},
	// 電話番号
	setTel(state, str){
		state.profile.tel = str;
	},
	// 学歴  
	setSchoolType(state, str){
		state.profile.school_type = str;
	},
	setSchoolName(state, str){
		state.profile.school_name = str;
	},
	setFacultyAndDepartment(state, str){
		state.profile.faculty_and_department = str;
	},
	setLiteratureOrScience(state, str){
		state.profile.literature_or_science = str;
	},
	setEnrollmentYear(state, num){
		state.profile.enrollment_year = num;
	},
	setGraduationYear(state, num){
		state.profile.graduation_year = num;
	},
	setGraduationType(state, str){
		state.profile.graduation_type = str;
	},
	// その他
	setJobChangeExperience(state, str){
		state.profile.job_change_experience = str;
	},
	setEmploymentStatus(state, str){
		state.profile.employment_status = str;
	},
	setLatestAnnualIncome(state, str){
		state.profile.latest_annual_income = str;
	},
	// 画像
	setIdPhoto(state, str){
		state.profile.id_photo = str;
	},
	setImageData(state, data){
		state.imageData = data;
	},
	setFile(state, file){
		state.file = file;
	},
	setBorderChange(state, bool){
		state.borderChange = bool;
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
	setErrorsZip(state, str){
		state.errors.zip.push(str);
	},
	resetErrorsZip(state, array){
		state.errors.zip = array;
	},
};

const actions = {
	// 年齢計算
	getAge: function (context) {
		let age = 'ー';
		let year = context.state.profile.year_of_birth;
		let month = context.state.profile.month_of_birth;
		let day = context.state.profile.date_of_birth;

		if(year && month && day){
			// 今日の日付データを取得
			const today = new Date();
			// 生年月日の日付データを取得
			const birthdate = new Date(year, month - 1, day);
			// 今年の誕生日の日付データを取得
			const currentYearBirthday = new Date(today.getFullYear(), birthdate.getMonth(), birthdate.getDate());
			
			// 生まれた年と今年の差を計算
			age = today.getFullYear() - birthdate.getFullYear();

			// 今日の日付と今年の誕生日を比較し、今年誕生日を迎えていない場合、1を引く
			if (today < currentYearBirthday) {
				age--;
			}
		}
		store.commit('EditProfile/setAge', age);
	},

	// 画像のドラッグ＆ドロップ
	dragenter() {
		store.commit('EditProfile/setBorderChange', true);
	},
	dragleave() {
		store.commit('EditProfile/setBorderChange', false);
	},

	// ファイル選択時の処理
	onFileChange(context, {e: e} ) {
		store.commit('EditProfile/setBorderChange', false);

		// ファイルを変数に格納
		store.commit('EditProfile/setFile', e.target.files[0]);

		// リアルタイムプレビュー
		const files = e.target.files;
		if(files.length > 0) {
			// ファイル情報を取得して画像を読み込む
			const file = files[0];
			const reader = new FileReader();

			reader.onload = (e) => {
				// データをimageDataに格納
				store.commit('EditProfile/setImageData', e.target.result);
			};
			reader.readAsDataURL(file);
		}
	},
	
	// プロフィール更新処理
	updateProfile: async function (context){
		// 処理中の画面表示
		store.commit('EditProfile/setIsLoading', true);

		// ボタン連打を無効化
		store.commit('EditProfile/set_isClicked', true);

		// 送信データを用意
		let formData = new FormData();

		formData.append('last_name', context.state.profile.last_name);
		formData.append('first_name', context.state.profile.first_name);
		formData.append('last_name_kana', context.state.profile.last_name_kana);
		formData.append('first_name_kana', context.state.profile.first_name_kana);
		formData.append('year_of_birth', context.state.profile.year_of_birth) ;
		formData.append('month_of_birth', context.state.profile.month_of_birth) ;
		formData.append('date_of_birth', context.state.profile.date_of_birth) ;
		formData.append('age', context.state.profile.age) ;
		formData.append('sex', context.state.profile.sex) ;
		formData.append('zip', context.state.profile.zip) ;
		formData.append('prefecture', context.state.profile.prefecture) ;
		formData.append('municipalities', context.state.profile.municipalities) ;
		formData.append('other_address', context.state.profile.other_address) ;
		formData.append('tel', context.state.profile.tel) ;
		formData.append('school_type', context.state.profile.school_type) ;
		formData.append('school_name', context.state.profile.school_name) ;
		formData.append('faculty_and_department', context.state.profile.faculty_and_department) ;
		formData.append('literature_or_science', context.state.profile.literature_or_science) ;
		formData.append('enrollment_year', context.state.profile.enrollment_year) ;
		formData.append('graduation_year', context.state.profile.graduation_year) ;
		formData.append('graduation_type', context.state.profile.graduation_type) ;
		formData.append('job_change_experience', context.state.profile.job_change_experience) ;
		formData.append('employment_status', context.state.profile.employment_status) ;
		formData.append('latest_annual_income', context.state.profile.latest_annual_income) ;
		formData.append('id_photo', context.state.file);
		// formData.append('testFlg', false);

		// エラー配列初期化
		store.commit('EditProfile/setErrors', {});

		// axiosで使うthisを格納
		const self = this;

		await axios.post('/job20/web/profile', formData)
		.then(response => {
			if(response.data.successFlg) {
				// ユーザーデータが保存できたら検索ページへ遷移
				if(response.data.newProfile) {
					if(response.data.editFlg){
						store.commit('Modal/setMessage', 'プロフィールを更新しました。');
					}else{
						store.commit('Modal/setMessage', 'プロフィールを登録しました。');
					}

					// フラッシュメッセージ表示
					store.commit('Modal/setFlash', true);
					// WEB履歴書ページへ遷移
					router.push({ name: 'web_resume', params: { propNum: 1 }});

					// データ削除
					store.commit('EditProfile/setProfile', {});
					store.commit('EditProfile/setImageData', '');
					store.commit('EditProfile/setFile', '');
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
			// 名前
			if(errors.last_name === undefined){
				errors.last_name = [];
			}
			if(errors.last_name_kana === undefined){
				errors.last_name_kana = [];
			}
			if(errors.first_name === undefined){
				errors.first_name = [];
			}
			if(errors.first_name_kana === undefined){
				errors.first_name_kana = [];
			}
			// 生年月日
			if(errors.year_of_birth === undefined){
				errors.year_of_birth = [];
			}
			if(errors.month_of_birth === undefined){
				errors.month_of_birth = [];
			}
			if(errors.date_of_birth === undefined){
				errors.date_of_birth = [];
			}
			// 性別
			if(errors.sex === undefined){
				errors.sex = [];
			}
			// 住所
			if(errors.zip === undefined){
				errors.zip = [];
			}
			if(errors.prefecture === undefined){
				errors.prefecture = [];
			}
			if(errors.municipalities === undefined){
				errors.municipalities = [];
			}
			if(errors.other_address === undefined){
				errors.other_address = [];
			}
			// 電話番号
			if(errors.tel === undefined){
				errors.tel = [];
			}
			// 学歴
			if(errors.school_type === undefined){
				errors.school_type = [];
			}
			if(errors.school_name === undefined){
				errors.school_name = [];
			}
			if(errors.faculty_and_department === undefined){
				errors.faculty_and_department = [];
			}
			if(errors.literature_or_science === undefined){
				errors.literature_or_science = [];
			}
			if(errors.enrollment_year === undefined){
				errors.enrollment_year = [];
			}
			if(errors.graduation_year === undefined){
				errors.graduation_year = [];
			}
			if(errors.graduation_type === undefined){
				errors.graduation_type = [];
			}
			// 画像
			if(errors.id_photo === undefined){
				errors.id_photo = [];
			}
			// その他
			if(errors.job_change_experience === undefined){
				errors.job_change_experience = [];
			}
			if(errors.employment_status === undefined){
				errors.employment_status = [];
			}
			if(errors.latest_annual_income === undefined){
				errors.latest_annual_income = [];
			}

			// stateに代入
			store.commit('EditProfile/setErrors', errors);

			// フォーム編集フラグは有効のままにする
			store.commit('FormChanged/set_formChanged', true);

			// トップにスクロール
			window.scrollTo({top: 0, behavior: 'smooth'})
		});

		// ボタンを初期状態に戻す
		store.commit('EditProfile/set_isClicked', false);
		
		// 処理中の画面を非表示
		store.commit('EditProfile/setIsLoading', false);
	},  
};

export default {
	namespaced: true,
	state,
	mutations,
	actions
};
