<script>
export default {
	data(){
		return {
			isDisabledZip: true,
		}
	},

	// リアルタイムバリデーション
	watch: {
		// 名前
		// 性
		last_name: {
			handler: function () {
				this.errors.last_name = [];

				// 正規表現パターンを定義(ひらがな・カタカナ・漢字を含む)
				const regex = /^[ぁ-んァ-ヶー一-龯]+$/;

				if(this.last_name){
					// console.log('a1:性がある場合');

					// 性が、ひらがな・カタカナ・漢字でない場合
					if(!regex.test(this.last_name)) {
						this.errors.last_name.push('性は、ひらがな・カタカナ・漢字で入力してください。');
						// console.log('a2:性が、ひらがな・カタカナ・漢字でない場合');

					// 性が20文字以上の場合
					}else if(this.last_name.length > 20){
						this.errors.last_name.push('性は、20文字以下で入力してください。');
						// console.log('a3:性が20文字以上の場合');
					}

				}else{
					this.errors.last_name.push('性は入力必須です。');
					// console.log('a4:性がない場合');
				}		
			},
			immediate: true,
		},

		// 名
		first_name: {
			handler: function () {
				this.errors.first_name = [];

				// 正規表現パターンを定義(ひらがな・カタカナ・漢字を含む)
				const regex = /^[ぁ-んァ-ヶー一-龯]+$/;

				if(this.first_name){
					// console.log('b1:名がある場合');

					// 名が、ひらがな・カタカナ・漢字でない場合
					if(!regex.test(this.first_name)) {
						this.errors.first_name.push('名は、ひらがな・カタカナ・漢字で入力してください。');
						// console.log('b2:名が、ひらがな・カタカナ・漢字でない場合');

					// 名が20文字以上の場合
					}else if(this.first_name.length > 20){
						this.errors.first_name.push('名は、20文字以下で入力してください。');
						// console.log('b3:名が20文字以上の場合');
					}

				}else{
					this.errors.first_name.push('名は入力必須です。');
					// console.log('b4:名がない場合');
				}
			},
			immediate: true,
		},

		// 性(カナ)
		last_name_kana: {
			handler: function () {
				this.errors.last_name_kana = [];

				// 正規表現パターンを定義(カタカナを含む)
				const regex = /^[ァ-ヶー]+$/;

				if(this.last_name_kana){
					// console.log('c1:性(カナ)がある場合');

					// 性(カナ)がカタカナでない場合
					if(!regex.test(this.last_name_kana)) {
						this.errors.last_name_kana.push('性(カナ)は、カタカナで入力してください。');
						// console.log('c2:性(カナ)がカタカナでない場合');

					// 性(カナ)が20文字以上の場合
					}else if(this.last_name_kana.length > 20){
						this.errors.last_name_kana.push('性(カナ)は、20文字以下で入力してください。');
						// console.log('c3:性(カナ)が20文字以上の場合');
					}

				}else{
					this.errors.last_name_kana.push('性(カナ)は入力必須です。');
					// console.log('c4:性(カナ)がない場合');
				}
			},
			immediate: true,
		},

		// 名(カナ)
		first_name_kana: {
			handler: function () {
				this.errors.first_name_kana = [];

				// 正規表現パターンを定義(カタカナを含む)
				const regex = /^[ァ-ヶー]+$/;

				if(this.first_name_kana){
					// console.log('d1:名(カナ)がある場合');

					// 名(カナ)がカタカナでない場合
					if(!regex.test(this.first_name_kana)) {
						this.errors.first_name_kana.push('名(カナ)は、カタカナで入力してください。');
						// console.log('d2:名(カナ)がカタカナでない場合');

					// 名(カナ)が20文字以上の場合
					}else if(this.first_name_kana.length > 20){
						this.errors.first_name_kana.push('名(カナ)は、20文字以下で入力してください。');
						// console.log('d3:名(カナ)が20文字以上の場合');
					}

				}else{
					this.errors.first_name_kana.push('名(カナ)は入力必須です。');
					// console.log('d4:名(カナ)がない場合');
				}
			},
			immediate: true,
		},

		// 生年月日
		// 生まれた年
		year_of_birth: {
			handler: function () {
				this.errors.year_of_birth = [];

				if(!this.year_of_birth){
					this.errors.year_of_birth.push('生まれた年は選択必須です。');
					// console.log('e1:生まれた年が入力されていない場合');
				}
			},
			immediate: true,
		},

		// 生まれた月
		month_of_birth: {
			handler: function () {
				this.errors.month_of_birth = [];

				if(!this.month_of_birth){
					this.errors.month_of_birth = [];
						this.errors.month_of_birth.push('生まれた月は選択必須です。');
					// console.log('f2:生まれた月が入力されていない場合');
				}
			},
			immediate: true,
		},

		// 生まれた日
		date_of_birth: {
			handler: function () {
				this.errors.date_of_birth = [];

				if(!this.date_of_birth){
					this.errors.date_of_birth = [];
						this.errors.date_of_birth.push('生まれた日は選択必須です。');
					// console.log('g3:生まれた日が入力されていない場合');
				}
			},
			immediate: true,
		},

		// 性別
		sex: {
			handler: function () {
				this.errors.sex = [];

				if(!this.sex){
					this.errors.sex.push('性別は選択必須です。');
					// console.log('性別が選択されていない場合');
				}
			},
			immediate: true,
		},

		// 現住所
		// 郵便番号
		zip: {
			handler: function () {
				this.errors.zip = [];

				// 正規表現パターンを定義
				const regex = /\d{3}-?\d{4}/;
				// const regex = /^0[0-9]{1}[1-7]{1}[0-9]{4}$/;

				// 郵便番号がある場合
				if(this.zip){
					// console.log('h1:郵便番号がある場合');

					// 郵便番号の形式が正しくない場合
					if(!regex.test(this.zip)){
						this.isDisabledZip = true;
						this.errors.zip.push('郵便番号の形式で入力してください。');
						// console.log('h2:郵便番号の形式が正しくない場合');

					// 郵便番号の形式が正しい場合
					}else{
						this.isDisabledZip = false;
						// console.log('h3:郵便番号の形式が正しい場合');
					}

				// 郵便番号がない場合
				}else{
					this.isDisabledZip = true;
					this.errors.zip.push('郵便番号は入力必須です。');
					// console.log('h4:郵便番号がない場合');
				}
			},
			immediate: true,
		},

		// 都道府県
		prefecture: {
			handler: function () {
				this.errors.prefecture = [];

				// 都道府県が入力されていない場合
				if(!this.prefecture){
					this.errors.prefecture.push('都道府県は入力必須です。');
					// console.log('都道府県が入力されていない場合');
				}
			},
			immediate: true,
		},

		// 市区町村
		municipalities: {
			handler: function () {
				this.errors.municipalities = [];

				// 市区町村が入力されていない場合
				if(!this.municipalities){
					this.errors.municipalities.push('市区町村は入力必須です。');
					// console.log('市区町村が入力されていない場合');

				// 市区町村が50文字以上の場合
				}else if(this.municipalities.length > 50){
					this.errors.municipalities.push('市区町村は、50文字以下で入力してください。');
					// console.log('市区町村が50文字以上の場合');
				}
			},
			immediate: true,
		},

		// その他住所
		other_address: {
			handler: function () {
				// console.log('other_address: ' + this.other_address);

				this.errors.other_address = [];

				// その他住所がある場合
				if(this.other_address){
					// console.log('その他住所がある場合');

					// その他住所が50文字以上の場合
					if(this.other_address.length > 50){
						this.errors.other_address.push('その他住所は、50文字以下で入力してください。');
						// console.log('その他住所が50文字以上の場合');
					}
					
				// その他住所がない場合
				}else{
					// console.log('その他住所がない場合');
				}
			},
			immediate: true,
		},

		// 電話番号
		tel: {
			handler: function () {
					this.errors.tel = [];

				// 正規表現パターンを定義
				const regex = /^[0-9]{10,11}$/;
				// const regex = /^0[1-9]{1}[0-9]{1}[0-9]{7,8}$/;


				// 電話番号がある場合
				if(this.tel){
					// console.log('電話番号がある場合');

					// 電話番号の形式が正しくない場合
					if(!regex.test(this.tel)){
						this.errors.tel.push('電話番号の形式で入力してください。');
						// console.log('電話番号の形式が正しくない場合');
					}

				// 電話番号がない場合
				}else{
					// console.log('電話番号がない場合');
					this.errors.tel.push('電話番号は入力必須です。');
				}
			},
			immediate: true,
		},

		// 最終学歴
		// 学校の種類
		school_type: {
			handler: function () {
				this.errors.school_type = [];

				if(!this.school_type){
					this.errors.school_type.push('学校の種類は選択必須です。');
					// console.log('学校の種類が選択されていない場合');
				}
			},
			immediate: true,
		},

		// 学校名
		school_name: {
			handler: function () {
				this.errors.school_name = [];

				// 学校名が入力されている場合
				if(this.school_name){
					// console.log('学校名が入力されている場合');

					// 学校名が50文字以上の場合
					if(this.school_name.length > 50){
						this.errors.school_name.push('学校名は、50文字以下で入力してください。');
						// console.log('学校名が50文字以上の場合');
					}

				// 学校名が入力されていない場合
				}else{
					this.errors.school_name.push('学校名は入力必須です。');
					// console.log('学校名が入力されていない場合');
				}
			},
			immediate: true,
		},

		// 学部・学科
		faculty_and_department: {
			handler: function () {
				this.errors.faculty_and_department = [];

				// 学部・学科が入力されている場合
				if(this.faculty_and_department){
					// console.log('学部・学科が入力されている場合');

					// 学部・学科が50文字以上の場合
					if(this.faculty_and_department.length > 50){
						this.errors.faculty_and_department.push('学部・学科は、50文字以下で入力してください。');
						// console.log('学部・学科が50文字以上の場合');
					}

				// 学部・学科が入力されていない場合
				}else{
					this.errors.faculty_and_department.push('学部・学科は入力必須です。');
					// console.log('学部・学科が入力されていない場合');
				}
			},
			immediate: true,
		},

		// 文理区分
		literature_or_science: {
			handler: function () {
				this.errors.literature_or_science = [];

				if(!this.literature_or_science){
					this.errors.literature_or_science.push('文理区分は選択必須です。');
					// console.log('文理区分が選択されていない場合');
				}
			},
			immediate: true,
		},

		// 在籍期間
		// 入学年
		enrollment_year: {
			handler: function () {
				this.errors.enrollment_year = [];
				this.errors.period = [];

				// 入学年がある場合
				if(this.enrollment_year){
					// console.log('i1:入学年あり');

					// 入学年が卒業年より大きい場合
					if(this.graduation_year && this.enrollment_year > this.graduation_year){
						this.errors.period.push('在籍期間を正しく入力してください。');
						// console.log('i2:入学年が卒業年より大きい場合');

					// 卒業年が入学年より小さい場合
					}else{
						this.errors.enrollment_year = [];
						this.errors.graduation_year = [];
						this.errors.period = [];
						// console.log('i4:卒業年が入学年より小さい場合');
					}

				// 入学年がない場合
				}else{
					this.errors.enrollment_year.push('入学年は選択必須です。');	
					// console.log('i5:入学年が入力されていない場合');
				}
			},
			immediate: true,
		},

		// 卒業年
		graduation_year: {
			handler: function () {
				this.errors.graduation_year = [];
				this.errors.period = [];

				// 卒業年がある場合
				if(this.graduation_year){
					// console.log('j1:卒業年あり');

					// 入学年が卒業年より大きい場合
					if(this.enrollment_year && this.enrollment_year > this.graduation_year){
						this.errors.period.push('在籍期間を正しく入力してください。');
						// console.log('j2:入学年が卒業年より大きい場合');

					// 卒業年が入学年より小さい場合
					}else{
						this.errors.enrollment_year = [];
						this.errors.graduation_year = [];
						this.errors.period = [];
						// console.log('j4:卒業年が入学年より小さい場合');
					}

				// 卒業年がない場合
				}else{
					this.errors.graduation_year.push('卒業年は選択必須です。');
					// console.log('j5:卒業年が入力されていない場合');
				}
			},
			immediate: true,
		},

		// 卒業区分
		graduation_type: {
			handler: function () {
				this.errors.graduation_type = [];

				if(!this.graduation_type){
					this.errors.graduation_type.push('卒業区分は選択必須です。');
					// console.log('卒業区分が選択されていない場合');
				}
			},
			immediate: true,
		},

		// その他
		// 転職経験
		job_change_experience: {
			handler: function () {
				this.errors.job_change_experience = [];

				if(!this.job_change_experience){
					this.errors.job_change_experience.push('転職経験は選択必須です。');
					// console.log('転職経験が選択されていない場合');
				}
			},
			immediate: true,
		},

		// 就業状況
		employment_status: {
			handler: function () {
				this.errors.employment_status = [];

				if(!this.employment_status){
					this.errors.employment_status.push('就業状況は選択必須です。');
					// console.log('就業状況が選択されていない場合');
				}
			},
			immediate: true,
		},

		// 直近の年収
		latest_annual_income: {
			handler: function () {
				this.errors.latest_annual_income = [];

				if(!this.latest_annual_income){
					this.errors.latest_annual_income.push('直近の年収は選択必須です。');
					// console.log('直近の年収が選択されていない場合');
				}
			},
			immediate: true,
		},

		// 画像
		file: {
			handler: function () {
				this.errors.id_photo = [];
			
				// 画像形式の判定
				const type = this.file.type;
				const typeArray = ['image/jpeg', 'jpeg', 'image/png', 'png', 'image/gif', 'gif', 'image/heic', 'heic'];
				const result = typeArray.includes(type);

				if(this.file) {
					// console.log('k1:画像がある場合');

					if(this.file.size > 7340032) {
						this.errors.id_photo.push('7MB以下の画像を選択してください。');
						// console.log('k2:画像サイズが7MB以上の場合');

					}else if(!result) {
						this.errors.id_photo.push('拡張子が「jpg,png,gif,heic」の画像を選択してください。');
						// console.log('k3:画像の拡張子が違う場合');
					}
				}else{
					this.errors.id_photo = [];
					// console.log('k4:画像がない場合');
				}
			},
			immediate: true,
		},

		profile: {
			handler: function () {
				if(	this.last_name && this.last_name_kana && this.first_name && this.first_name_kana && 
					this.year_of_birth && this.month_of_birth && this.date_of_birth && this.sex && 
					this.zip && this.prefecture && this.municipalities && this.tel && 
					this.school_type && this.school_name && this.faculty_and_department && 
					this.literature_or_science && this.enrollment_year && this.graduation_year && this.graduation_type && 
					this.job_change_experience && this.employment_status && this.latest_annual_income && 
				   !this.getErrorMessage(this.errors.last_name) && !this.getErrorMessage(this.errors.last_name_kana) && 
				   !this.getErrorMessage(this.errors.first_name) && !this.getErrorMessage(this.errors.first_name_kana) && 
				   !this.getErrorMessage(this.errors.year_of_birth) && !this.getErrorMessage(this.errors.month_of_birth) && 
				   !this.getErrorMessage(this.errors.date_of_birth) && !this.getErrorMessage(this.errors.sex) && 
				   !this.getErrorMessage(this.errors.zip) && !this.getErrorMessage(this.errors.prefecture) && 
				   !this.getErrorMessage(this.errors.municipalities) && !this.getErrorMessage(this.errors.other_address) && 
				   !this.getErrorMessage(this.errors.tel) && !this.getErrorMessage(this.errors.school_type) && 
				   !this.getErrorMessage(this.errors.school_name) && !this.getErrorMessage(this.errors.faculty_and_department) && 
				   !this.getErrorMessage(this.errors.literature_or_science) && !this.getErrorMessage(this.errors.enrollment_year) && 
				   !this.getErrorMessage(this.errors.graduation_year) && !this.getErrorMessage(this.errors.graduation_type) && 
				   !this.getErrorMessage(this.errors.job_change_experience) && !this.getErrorMessage(this.errors.employment_status) && 
				   !this.getErrorMessage(this.errors.latest_annual_income) && !this.getErrorMessage(this.errors.id_photo)){

					this.isDisabled = false;
				}else{
					this.isDisabled = true;
				}
			},
			deep: true,
			immediate: true,
		},
	},
}
</script>

<style>
.disabledZip{
	background: #c5c5c5;
	border: 1px solid white;
	box-shadow: 0 3px 10px rgba(0,0,0,0.1);
	color: white;
}
.disabledZip:hover{
	cursor: not-allowed;
}
</style>
