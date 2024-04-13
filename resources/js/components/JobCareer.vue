<template>
	<div>
		<transition name="modal">
			<Modal v-show="isShowModal">
				<template slot="body">
					<div class="c-modal__body">
						<p class="c-modal__text">職務経歴（{{ modalDeleteNum() }}社目）を削除します。<br>よろしいですか？</p>
						<div class="c-modal__buttonArea" >
							<button class="c-modal__button" @click="deleteJobCareer(modalDeleteID()), close()">はい</button>
							<button class="c-modal__button--no" @click.prevent="close()">いいえ</button>
						</div>
					</div>
				</template>
			</Modal>
		</transition>

		<div class="c-form--borderTopNone p-jobCareer">

			<!-- 職務経歴が登録済みの場合 -->
			<template v-if="countJobCareers() !== 0">
				<div v-for="(job_career, index) in job_careers" :key="index" class="u-mb20">
					<h3 class="c-form__title--jobCareer">
						<span class="p-jobCareer__titleText">職務経歴 ({{ index+1 }}社目)</span>
						<span class="p-jobCareer__titleBtnArea">
							<router-link class="c-btn--edit" :to="{ name: 'edit_job_career', params: { id: job_career.id, propJobCareer: job_career } }">
								<i class="fa-solid fa-pen"></i>編集<span class="u-tabHidden">する</span>
							</router-link>
							<a class="c-btn--delete" @click.prevent="open(index+1, job_career.id)">
								<i class="fa-solid fa-circle-xmark"></i>削除<span class="u-tabHidden">する</span>
							</a>
						</span>
					</h3>

					<table class="c-table--showWebResume">
						<tbody>
							<tr class="c-table__tr">
								<th class="c-table__th">
									<span class="c-table__thTitle">社名</span>
								</th>
								<td class="c-table__td">{{ job_career.job_careers_company_name }}</td>
							</tr>
							<tr class="c-table__tr">
								<th class="c-table__th">
									<span class="c-table__thTitle">在籍期間</span>
								</th>
								<td class="c-table__td">{{ job_career.year_of_joining }}年 {{ job_career.month_of_joining }}月 ~ {{ job_career.year_of_retirement }}年 {{ job_career.month_of_retirement }}月
								</td>
							</tr>
							<tr class="c-table__tr">
								<th class="c-table__th">
									<span class="c-table__thTitle">雇用形態</span>
								</th>
								<td class="c-table__td">{{ job_career.employment_status }}</td>
							</tr>
							<tr class="c-table__tr">
								<th class="c-table__th">
									<span class="c-table__thTitle">業種</span>
								</th>
								<td class="c-table__td">{{ job_career.job_careers_industry }}</td>
							</tr>
							<tr class="c-table__tr">
								<th class="c-table__th">
									<span class="c-table__thTitle">従業員数</span>
								</th>
								<td class="c-table__td">
									<template v-if="job_career.number_of_employees">{{ job_career.number_of_employees }}</template>
									<template v-else>---</template>
								</td>
							</tr>
							<tr class="c-table__tr">
								<th class="c-table__th">
									<span class="c-table__thTitle">部署/役職</span>
								</th>
								<td class="c-table__td">
									<template v-if="job_career.department_or_post">{{ job_career.department_or_post }}</template>
									<template v-else>---</template>
								</td>
							</tr>
							<tr class="c-table__tr">
								<th class="c-table__th">
									<span class="c-table__thTitle">職務内容</span>
								</th>
								<td class="c-table__td--textarea">
									<template v-if="job_career.job_details">{{ job_career.job_details }}</template>
									<template v-else>---</template>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</template>

			<!-- 職務経歴が未登録の場合 -->
			<div v-else class="c-noData">
				職務経歴は<span class="u-spShow"><br></span>登録されていません。
			</div>

			<!-- ボタンエリア -->
			<div class="c-form__alignCenterArea">
				<router-link v-if="countJobCareers() < 5" class="c-btn--m" :to="{ name: 'edit_job_career' }">
					<template v-if="countJobCareers() !== 5">追加</template><template v-else>登録</template>画面へ進む</router-link>
				<div v-else class="c-noData">※ 職務経歴は最大5枠のため、これ以上追加できません。</div>
			</div>
		</div>		
	</div>
</template>

<script>
import Modal from './Modal.vue';

export default {
	components: {
		"Modal": Modal,
	},
	props: [
		'job_careers',
	],
	data(){
		return {
		}
	},
	
	methods:{
		// モーダル開閉
		open(propNum, propID) {
			this.$store.commit('Modal/open', { modalName : "delete", num: propNum, id: propID });
        },
		close() {
			this.$store.commit('Modal/close', { modalName : "delete" });
        },

		// 所有する職務経歴数を返却
		countJobCareers(){
			return this.job_careers.length;
		},

		// モーダル情報を返却
		modalDeleteNum: function () {
			return this.$store.state.Modal.modal.delete.num;
		},
		modalDeleteID: function () {
			return this.$store.state.Modal.modal.delete.id;
		},

		// 職務経歴の削除
		deleteJobCareer: async function (id){
			// axiosで使うthisを格納
			const self = this;

			await axios.delete('/job20/web/job_career/' + id)
			.then(response => {
				if(response.data.successFlg && response.data.deleteFlg === 0){
					// ローカルストレージにフラッシュメッセージを保存
					localStorage.setItem(['flash_message'],['職務経歴を削除しました。']);
					// 履歴を残さずリダイレクト（ブラウザバックできなくなる）
					location.replace('/job20/web_resume');

					// vuexのデータ削除
					this.$store.commit('EditJobCareer/set_job_careers_company_name', '');
					this.$store.commit('EditJobCareer/set_year_of_joining', '');
					this.$store.commit('EditJobCareer/set_month_of_joining', '');
					this.$store.commit('EditJobCareer/set_year_of_retirement', '');
					this.$store.commit('EditJobCareer/set_month_of_retirement', '');
					this.$store.commit('EditJobCareer/set_employment_status', '');
					this.$store.commit('EditJobCareer/set_job_careers_industry', '');
					this.$store.commit('EditJobCareer/set_number_of_employees', '');
					this.$store.commit('EditJobCareer/set_department_or_post', '');
					this.$store.commit('EditJobCareer/set_job_details', '');					
					this.$store.commit('EditJobCareer/set_textarea_height', "auto");
									
				}else{
					self.$router.push({ name: 'error' });
				}
			}).catch(error => console.log(error) );
		},
	},

	computed:{
		// モーダル表示判定フラグ
		isShowModal: function () {
			return this.$store.state.Modal.modal.delete.isShow;
		},
	},
}
</script>
