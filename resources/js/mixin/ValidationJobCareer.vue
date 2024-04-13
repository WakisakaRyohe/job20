<script>
export default {
	data(){
		return {
			isInput: true,
		}
	},

	// リアルタイムバリデーション
	watch: {
		// 社名
		job_careers_company_name: {
			handler: function () {
				this.errors.job_careers_company_name = [];

				// 社名がない場合
				if(!this.job_careers_company_name) {
					this.errors.job_careers_company_name.push('社名は入力必須です。');
					// console.log('a1:社名がない場合');

				// 社名が50文字以上の場合
				}else if(this.job_careers_company_name.length > 50){
					this.errors.job_careers_company_name.push('社名は、50文字以下で入力してください。');
					// console.log('a2:社名が50文字以上の場合');
				}
			},
			immediate: true,
		},

		// ================================================
		// 入社年
		// ================================================
		year_of_joining: {
			handler: function () {
				this.errors.year_of_joining = [];
				this.errors.period = [];

				// 入社年がある場合
				if(this.year_of_joining){
					// console.log('c1:入社年がある場合');

					// 在籍期間が全て入力されている場合
					if(this.year_of_joining && this.year_of_retirement && 
					   this.month_of_joining && this.month_of_retirement){
						// console.log('c2:在籍期間が全て入力されている場合');

						// 入社年が退社年より大きい場合
						if(this.year_of_joining > this.year_of_retirement){
							this.errors.period.push('在籍期間を正しく入力してください。');
							// console.log('c3:入社年が退社年より大きい場合');

						// 入社年と退社年が同じで、入社月が退社月より大きい場合
						}else if( (this.year_of_joining === this.year_of_retirement) && 
								(this.month_of_joining >= this.month_of_retirement) ){
							this.errors.period.push('在籍期間を正しく入力してください。');
							// console.log('c4:入社年と退社年が同じで、入社月が退社月より大きい場合');
						}
					}

				// 入社年がない場合
				}else{
					this.errors.year_of_joining.push('入社年は選択必須です。');
					// console.log('c5:入社年がない場合');
				}
			},
			immediate: true,
		},

		// ================================================
		// 入社月
		// ================================================
		month_of_joining: {
			handler: function () {
				this.errors.month_of_joining = [];
				this.errors.period = [];

				// 入社月がある場合
				if(this.month_of_joining){
					// console.log('d1:入社月がある場合');

					// 在籍期間が全て入力されている場合
					if(this.year_of_joining && this.year_of_retirement && 
					   this.month_of_joining && this.month_of_retirement){
						// console.log('d2:在籍期間が全て入力されている場合');

						// 入社年が退社年より大きい場合
						if(this.year_of_joining > this.year_of_retirement){
							this.errors.period.push('在籍期間を正しく入力してください。');
							// console.log('d3:入社年が退社年より大きい場合');

						// 入社年と退社年が同じで、入社月が退社月より大きい場合
						}else if( (this.year_of_joining === this.year_of_retirement) && 
								(this.month_of_joining >= this.month_of_retirement) ){
							this.errors.period.push('在籍期間を正しく入力してください。');
							// console.log('d4:入社年と退社年が同じで、入社月が退社月より大きい場合');
						}
					}

				// 入社月がない場合
				}else{
					this.errors.month_of_joining.push('入社月は選択必須です。');
					// console.log('d5:入社月がない場合');
				}
			},
			immediate: true,
		},

		// ================================================
		// 退社年
		// ================================================
		year_of_retirement: {
			handler: function () {
				this.errors.year_of_retirement = [];
				this.errors.period = [];

				// 退社年がある場合
				if(this.year_of_retirement){
					// console.log('e1:退社年がある場合');

					// 在籍期間が全て入力されている場合
					if(this.year_of_joining && this.year_of_retirement && 
					   this.month_of_joining && this.month_of_retirement){
						// console.log('e2:在籍期間が全て入力されている場合');

						// 入社年が退社年より大きい場合
						if(this.year_of_joining > this.year_of_retirement){
							this.errors.period.push('在籍期間を正しく入力してください。');
							// console.log('e3:入社年が退社年より大きい場合');

						// 入社年と退社年が同じで、入社月が退社月より大きい場合
						}else if( (this.year_of_joining === this.year_of_retirement) && 
								(this.month_of_joining >= this.month_of_retirement) ){
							this.errors.period.push('在籍期間を正しく入力してください。');
							// console.log('e4:入社年と退社年が同じで、入社月が退社月より大きい場合');
						}
					}

				// 退社年がない場合
				}else{
					this.errors.year_of_retirement.push('退社年は選択必須です。');
					// console.log('e5:退社年がない場合');
				}
			},
			immediate: true,
		},

		// ================================================
		// 退社月
		// ================================================
		month_of_retirement: {
			handler: function () {
				this.errors.month_of_retirement = [];
				this.errors.period = [];

				// 退社月がある場合
				if(this.month_of_retirement){
					// console.log('f1:退社月がある場合');

					// 在籍期間が全て入力されている場合
					if(this.year_of_joining && this.year_of_retirement && 
					   this.month_of_joining && this.month_of_retirement){
						// console.log('f2:在籍期間が全て入力されている場合');

						// 入社年が退社年より大きい場合
						if(this.year_of_joining > this.year_of_retirement){
							this.errors.period.push('在籍期間を正しく入力してください。');
							// console.log('f3:入社年が退社年より大きい場合');

						// 入社年と退社年が同じで、入社月が退社月より大きい場合
						}else if( (this.year_of_joining === this.year_of_retirement) && 
								(this.month_of_joining >= this.month_of_retirement) ){
							this.errors.period.push('在籍期間を正しく入力してください。');
							// console.log('f4:入社年と退社年が同じで、入社月が退社月より大きい場合');
						}
					}

				// 退社月がない場合
				}else{
					this.errors.month_of_retirement.push('退社月は選択必須です。');
					// console.log('f5:退社月がない場合');
				}
			},
			immediate: true,
		},

		// 雇用形態
		employment_status: {
			handler: function () {
				this.errors.employment_status = [];

				// 雇用形態が選択されていない場合
				if(!this.employment_status){
					this.errors.employment_status.push('雇用形態は選択必須です。');
					// console.log('雇用形態が選択されていない場合');
				}
			},
			immediate: true,
		},

		// 業種
		job_careers_industry: {
			handler: function () {
				this.errors.job_careers_industry = [];

				// 業種が選択されていない場合
				if(!this.job_careers_industry){
					this.errors.job_careers_industry.push('業種は選択必須です。');
					// console.log('業種が選択されていない場合');
				}
			},
			immediate: true,
		},

		// 従業員数(エラー入れなくても設定しておかないとエラーになる)
		number_of_employees: {
			handler: function () {
				this.errors.number_of_employees = [];

				// 従業員数が選択されていない場合
				if(!this.number_of_employees){
					// console.log('従業員数が選択されていない場合');
				}
			},
			immediate: true,
		},

		// 部署/役職
		department_or_post: {
			handler: function () {
				this.errors.department_or_post = [];

				// 部署/役職が50文字以上の場合
				if(this.department_or_post && this.department_or_post.length > 50){
					this.errors.department_or_post.push('部署/役職は、50文字以下で入力してください。');
					// console.log('部署/役職が50文字以上の場合');
				}
			},
			immediate: true,
		},

		// 職務内容
		job_details: {
			handler: function () {
				// テキストエリアの高さ変更
				this.resizeTextarea();
				// エラー配列を空にする
				this.errors.job_details = [];
				// 正規表現パターンを定義(改行とスペース以外の文字を含む)
				const regex = /.*[ぁ-んァ-ヶー一-龯0-9０-９a-zA-Z!?_+*'"`#$%&\-^\\@;:,./=~|[\](){}<>¥！-／：-＠［-｀｛-～、-〜”’・￥].*/;

				// 職務内容がある場合
				if(this.job_details){
					this.isInput = true;
					// console.log('b1:職務内容がある場合');

					// 職務内容が改行とスペース以外の文字を含まない場合
					if( !regex.test(this.job_details) ){
						// 入力値は空判定
						this.isInput = false;
						// console.log('b2:職務内容が改行とスペース以外の文字を含まない場合');
						// console.log('入力値: ' + this.isInput);

					// 職務内容が2000文字以上の場合
					}else if(this.job_details.length > 2000){
						// console.log('b3:職務内容が2000文字以上の場合');
						this.errors.job_details.push('職務内容は、2000文字以下で入力してください。');
					}

				// 職務内容がない場合
				}else{
					// console.log('b4:職務内容がない場合');
					this.isInput = true;
				}
			},
			immediate: true,
		},

		job_career: {
			handler: function () {
				if(	this.isInput && this.job_careers_company_name &&
					this.year_of_joining && this.month_of_joining && 
					this.year_of_retirement && this.month_of_retirement && 
					this.employment_status && this.job_careers_industry && 
				   !this.getErrorMessage(this.errors.job_careers_company_name) && 
				   !this.getErrorMessage(this.errors.year_of_joining) && !this.getErrorMessage(this.errors.month_of_joining) && 
				   !this.getErrorMessage(this.errors.year_of_retirement) && !this.getErrorMessage(this.errors.month_of_retirement) && 
				   !this.getErrorMessage(this.errors.employment_status) && !this.getErrorMessage(this.errors.job_careers_industry) && 
				   !this.getErrorMessage(this.errors.number_of_employees) && !this.getErrorMessage(this.errors.department_or_post) && 
				   !this.getErrorMessage(this.errors.job_details) ){
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
