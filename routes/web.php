<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
// リダイレクト設定
route::redirect('/woa/sp', '/woa', 301);
route::redirect('/woa/t_1.html', '/woa/job/judoseifukushi/tokyo', 301);
route::redirect('/woa/t_2.html', '/woa/job/ammamassajishiatsushi/tokyo', 301);
route::redirect('/woa/t_3.html', '/woa/job/harikyushi/tokyo', 301);
route::redirect('/woa/t_4.html', '/woa/job/seitaishi_therapist/tokyo', 301);
route::redirect('/woa/t_5.html', '/woa/job/aroma/tokyo', 301);
route::redirect('/woa/t_7.html', '/woa/job/other/tokyo', 301);
route::redirect('/woa/k_1.html', '/woa/job/judoseifukushi/kanagawa', 301);
route::redirect('/woa/k_2.html', '/woa/job/ammamassajishiatsushi/kanagawa', 301);
route::redirect('/woa/k_3.html', '/woa/job/harikyushi/kanagawa', 301);
route::redirect('/woa/k_4.html', '/woa/job/seitaishi_therapist/kanagawa', 301);
route::redirect('/woa/k_5.html', '/woa/job/aroma/kanagawa', 301);
route::redirect('/woa/k_7.html', '/woa/job/other/kanagawa', 301);
route::redirect('/woa/c_1.html', '/woa/job/judoseifukushi/chiba', 301);
route::redirect('/woa/c_2.html', '/woa/job/ammamassajishiatsushi/chiba', 301);
route::redirect('/woa/c_3.html', '/woa/job/harikyushi/chiba', 301);
route::redirect('/woa/c_4.html', '/woa/job/seitaishi_therapist/chiba', 301);
route::redirect('/woa/c_5.html', '/woa/job/aroma/chiba', 301);
route::redirect('/woa/c_7.html', '/woa/job/other/chiba', 301);
route::redirect('/woa/s_1.html', '/woa/job/judoseifukushi/saitama', 301);
route::redirect('/woa/s_2.html', '/woa/job/ammamassajishiatsushi/saitama', 301);
route::redirect('/woa/s_3.html', '/woa/job/harikyushi/saitama', 301);
route::redirect('/woa/s_4.html', '/woa/job/seitaishi_therapist/saitama', 301);
route::redirect('/woa/s_5.html', '/woa/job/aroma/saitama', 301);
route::redirect('/woa/s_7.html', '/woa/job/other/saitama', 301);

route::redirect('/woa/recruitcat/work_jyudo/', '/woa/job/judoseifukushi', 301);
route::redirect('/woa/recruitcat/work_hari/', '/woa/job/harikyushi', 301);
route::redirect('/woa/recruitcat/work_anma/', '/woa/job/ammamassajishiatsushi', 301);
route::redirect('/woa/recruitcat/work_seitai/', '/woa/job/seitaishi_therapist', 301);
route::redirect('/woa/recruitcat/work_therapy/', '/woa/job/chiropractic', 301);
route::redirect('/woa/recruitcat/work_other/', '/woa/job/other', 301);

route::redirect('/woa/mmentry.html', '/woa/kaitousokuhou', 301);
route::redirect('/woa/sp/mmentry.html', '/woa/kaitousokuhou', 301);
route::redirect('/woa/sp/entry/', '/woa/service', 301);
route::redirect('/woa/support/guide/', '/woa/guide', 301);
route::redirect('/woa/can/', '/woa/about', 301);
route::redirect('/woa/can/tour/', '/woa/about/tour', 301);
route::redirect('/woa/can/seminar/', '/woa/about/tour', 301);
route::redirect('/woa/can/write/', '/woa/about/write', 301);
route::redirect('/woa/can/practice/', '/woa/about/practice', 301);
route::redirect('/woa/can/consultation/', '/woa/about/consultation', 301);
route::redirect('/woa/flow/', '/woa/about/flow', 301);
route::redirect('/woa/support/', '/woa/about/support', 301);
route::redirect('/woa/recommended/', '/woa/recommended', 301);
route::redirect('/woa/saiyo/', '/woa/recruit', 301);

route::redirect('/woa/sp/can/', '/woa/about', 301);
route::redirect('/woa/sp/can/tour/', '/woa/about/tour', 301);
route::redirect('/woa/sp/can/seminar/', '/woa/about/tour', 301);
route::redirect('/woa/sp/can/write/', '/woa/about/write', 301);
route::redirect('/woa/sp/can/practice/', '/woa/about/practice', 301);
route::redirect('/woa/sp/can/consultation/', '/woa/about/consultation', 301);
route::redirect('/woa/sp/flow/', '/woa/about/flow', 301);
route::redirect('/woa/sp/support/', '/woa/about/support', 301);
route::redirect('/woa/sp/recommended/', '/woa/recommended', 301);
route::redirect('/woa/sp/saiyo/', '/woa/recruit', 301);

route::redirect('/woa/kaitousakuho_1.html', '/woa/kaitousokuhou/judoseifukushi19', 301);

route::redirect('/woa/kaitousakuho_2.html', '/woa/kaitousokuhou/anma19', 301);

route::redirect('/woa/kaitousakuho_3.html', '/woa/kaitousokuhou/hari19', 301);

route::redirect('/woa/kaitousokuho_anma_20.html', '/woa/kaitousokuhou/anma20', 301);

route::redirect('/woa/kaitousokuho_anma_21.html', '/woa/kaitousokuhou/anma21', 301);

route::redirect('/woa/kaitousokuho_anma_22.html', '/woa/kaitousokuhou/anma22', 301);

route::redirect('/woa/kaitousokuho_anma_23.html', '/woa/kaitousokuhou/anma23', 301);

route::redirect('/woa/kaitousokuho_anma_24.html', '/woa/kaitousokuhou/anma24', 301);

route::redirect('/woa/kaitousokuho_anma_25.html', '/woa/kaitousokuhou/anma25', 301);

route::redirect('/woa/kaitousokuho_anma_26.html', '/woa/kaitousokuhou/anma26', 301);

route::redirect('/woa/kaitousokuho_hari_20.html', '/woa/kaitousokuhou/hari20', 301);

route::redirect('/woa/kaitousokuho_hari_21.html', '/woa/kaitousokuhou/hari21', 301);

route::redirect('/woa/kaitousokuho_hari_22.html', '/woa/kaitousokuhou/hari22', 301);

route::redirect('/woa/kaitousokuho_hari_23.html', '/woa/kaitousokuhou/hari23', 301);

route::redirect('/woa/kaitousokuho_hari_24.html', '/woa/kaitousokuhou/hari24', 301);

route::redirect('/woa/kaitousokuho_hari_25.html', '/woa/kaitousokuhou/hari25', 301);

route::redirect('/woa/kaitousokuho_hari_26.html', '/woa/kaitousokuhou/hari26', 301);

route::redirect('/woa/kaitousokuho_jyudo__22.html', '/woa/kaitousokuhou/judoseifukushi22', 301);

route::redirect('/woa/kaitousokuho_jyudou_20.html', '/woa/kaitousokuhou/judoseifukushi20', 301);

route::redirect('/woa/kaitousokuho_jyudou_21.html', '/woa/kaitousokuhou/judoseifukushi21', 301);

route::redirect('/woa/kaitousokuho_jyudou_22.html', '/woa/kaitousokuhou/judoseifukushi22', 301);

route::redirect('/woa/kaitousokuho_jyudou_23.html', '/woa/kaitousokuhou/judoseifukushi23', 301);

route::redirect('/woa/kaitousokuho_jyudou_24.html', '/woa/kaitousokuhou/judoseifukushi24', 301);

route::redirect('/woa/kaitousokuho_jyudou_25.html', '/woa/kaitousokuhou/judoseifukushi25', 301);

route::redirect('/woa/kaitousokuho_jyudou_26.html', '/woa/kaitousokuhou/judoseifukushi26', 301);

route::redirect('/woa/mmentry.html/kaitousokuho_anma_26.html', '/woa/kaitousokuhou/anma26', 301);

route::redirect('/woa/blog/5098', '/woa/knowhow/career/5098', 301);
route::redirect('/woa/blog/column3/', '/woa/knowhow/career/5098', 301);
route::redirect('/woa/blog/5282', '/woa/knowhow/career/5282', 301);
route::redirect('/woa/blog/judoseihuku_1day/', '/woa/knowhow/career/5282', 301);
route::redirect('/woa/blog/judo_1day', '/woa/knowhow/career/5282', 301);
route::redirect('/woa/blog/5372', '/woa/knowhow/career/5372', 301);
route::redirect('/woa/blog/judo_salary', '/woa/knowhow/career/5372', 301);
route::redirect('/woa/blog/judoseifuku_salary/', '/woa/knowhow/career/5372', 301);
route::redirect('/woa/blog/5430', '/woa/knowhow/career/5430', 301);
route::redirect('/woa/blog/judo_holiday', '/woa/knowhow/career/5430', 301);
route::redirect('/woa/blog/judoseifuku_holiday/', '/woa/knowhow/career/5430', 301);
route::redirect('/woa/blog/5462', '/woa/knowhow/career/5462', 301);
route::redirect('/woa/blog/judoseifuku_trouble/', '/woa/knowhow/career/5462', 301);
route::redirect('/woa/blog/judo_trouble', '/woa/knowhow/career/5462', 301);
route::redirect('/woa/blog/6695', '/woa/knowhow/career/6695', 301);
route::redirect('/woa/blog/acupuncture_period', '/woa/knowhow/career/6695', 301);
route::redirect('/woa/blog/shinkyu-debut', '/woa/knowhow/career/6695', 301);
route::redirect('/woa/blog/hari_period', '/woa/knowhow/career/6695', 301);
route::redirect('/woa/blog/7127', '/woa/knowhow/career/7127', 301);
route::redirect('/woa/blog/7133', '/woa/knowhow/career/7133', 301);
route::redirect('/woa/blog/7142', '/woa/knowhow/career/7142', 301);
route::redirect('/woa/blog/7153', '/woa/knowhow/career/7153', 301);
route::redirect('/woa/blog/7209', '/woa/knowhow/career/7209', 301);
route::redirect('/woa/blog/7213', '/woa/knowhow/career/7213', 301);
route::redirect('/woa/blog/jusei-top-work', '/woa/knowhow/career/7213', 301);
route::redirect('/woa/blog/7218', '/woa/knowhow/career/7218', 301);
route::redirect('/woa/blog/movingandTransportation+cost', '/woa/knowhow/career/7218', 301);
route::redirect('/woa/blog/7223', '/woa/knowhow/career/7223', 301);
route::redirect('/woa/blog/singlelife', '/woa/knowhow/career/7223', 301);
route::redirect('/woa/blog/7278', '/woa/knowhow/career/7278', 301);
route::redirect('/woa/blog/7290', '/woa/knowhow/career/7290', 301);
route::redirect('/woa/blog/7292', '/woa/knowhow/career/7292', 301);
route::redirect('/woa/blog/judo_bonus', '/woa/knowhow/career/7292', 301);
route::redirect('/woa/blog/7303', '/woa/knowhow/career/7303', 301);
route::redirect('/woa/blog/sinkyu-freecost', '/woa/knowhow/career/7303', 301);
route::redirect('/woa/blog/7313', '/woa/knowhow/career/7313', 301);
route::redirect('/woa/blog/internalevent', '/woa/knowhow/career/7313', 301);
route::redirect('/woa/blog/7319', '/woa/knowhow/career/7319', 301);
route::redirect('/woa/blog/movingassistance', '/woa/knowhow/career/7319', 301);
route::redirect('/woa/blog/7320', '/woa/knowhow/career/7320', 301);
route::redirect('/woa/blog/7327', '/woa/knowhow/career/7327', 301);
route::redirect('/woa/blog/7334', '/woa/knowhow/career/7334', 301);
route::redirect('/woa/blog/7372', '/woa/knowhow/career/7372', 301);
route::redirect('/woa/blog/7381', '/woa/knowhow/career/7381', 301);
route::redirect('/woa/blog/7387', '/woa/knowhow/career/7387', 301);
route::redirect('/woa/blog/hari_holiday', '/woa/knowhow/career/7387', 301);
route::redirect('/woa/blog/7393', '/woa/knowhow/career/7393', 301);
route::redirect('/woa/blog/judo_maternityleave', '/woa/knowhow/career/7393', 301);
route::redirect('/woa/blog/7403', '/woa/knowhow/career/7403', 301);
route::redirect('/woa/blog/woman-work.shinkyu', '/woa/knowhow/career/7403', 301);
route::redirect('/woa/blog/7414', '/woa/knowhow/career/7414', 301);
route::redirect('/woa/blog/genre-shinkyu', '/woa/knowhow/career/7414', 301);
route::redirect('/woa/blog/7423', '/woa/knowhow/career/7423', 301);
route::redirect('/woa/blog/7431', '/woa/knowhow/career/7431', 301);
route::redirect('/woa/blog/woman-symptom', '/woa/knowhow/career/7431', 301);
route::redirect('/woa/blog/7435', '/woa/knowhow/career/7435', 301);
route::redirect('/woa/blog/shinkyu-beauty', '/woa/knowhow/career/7435', 301);
route::redirect('/woa/blog/7439', '/woa/knowhow/career/7439', 301);
route::redirect('/woa/blog/7462', '/woa/knowhow/career/7462', 301);
route::redirect('/woa/blog/acupuncture-group', '/woa/knowhow/career/7462', 301);
route::redirect('/woa/blog/7480', '/woa/knowhow/career/7480', 301);
route::redirect('/woa/blog/careerstep', '/woa/knowhow/career/7480', 301);
route::redirect('/woa/blog/7495', '/woa/knowhow/career/7495', 301);
route::redirect('/woa/blog/sports', '/woa/knowhow/career/7495', 301);
route::redirect('/woa/blog/7507', '/woa/knowhow/career/7507', 301);
route::redirect('/woa/blog/7515', '/woa/knowhow/career/7515', 301);
route::redirect('/woa/blog/mostshinkyu', '/woa/knowhow/career/7515', 301);
route::redirect('/woa/blog/7526', '/woa/knowhow/career/7526', 301);
route::redirect('/woa/blog/shinkyu-many', '/woa/knowhow/career/7526', 301);
route::redirect('/woa/blog/acupuncturist-president', '/woa/knowhow/career/7526', 301);
route::redirect('/woa/blog/7536', '/woa/knowhow/career/7536', 301);
route::redirect('/woa/blog/7552', '/woa/knowhow/career/7552', 301);
route::redirect('/woa/blog/7559', '/woa/knowhow/career/7559', 301);
route::redirect('/woa/blog/shinkyu-zippi', '/woa/knowhow/career/7559', 301);
route::redirect('/woa/blog/7563', '/woa/knowhow/career/7563', 301);
route::redirect('/woa/blog/judo_seikeigeka', '/woa/knowhow/career/7563', 301);
route::redirect('/woa/blog/jyusei_tennshoku_seikeigeka', '/woa/knowhow/career/7563', 301);
route::redirect('/woa/blog/judo/seikeigeka/', '/woa/knowhow/career/7563', 301);
route::redirect('/woa/blog/7578', '/woa/knowhow/career/7578', 301);
route::redirect('/woa/blog/welfare-shinkyu', '/woa/knowhow/career/7578', 301);
route::redirect('/woa/blog/hari_welfare', '/woa/knowhow/career/7578', 301);
route::redirect('/woa/blog/7582', '/woa/knowhow/career/7582', 301);
route::redirect('/woa/blog/jyusei_shinkyu_tennshoku_probation', '/woa/knowhow/career/7582', 301);
route::redirect('/woa/blog/7655', '/woa/knowhow/career/7655', 301);
route::redirect('/woa/blog/7657', '/woa/knowhow/career/7657', 301);
route::redirect('/woa/blog/judo_seikotuinn', '/woa/knowhow/career/7657', 301);
route::redirect('/woa/blog/7682', '/woa/knowhow/career/7682', 301);
route::redirect('/woa/blog/judo_trend', '/woa/knowhow/career/7682', 301);
route::redirect('/woa/blog/7687', '/woa/knowhow/career/7687', 301);
route::redirect('/woa/blog/judo_dayservice', '/woa/knowhow/career/7687', 301);
route::redirect('/woa/blog/7724', '/woa/knowhow/career/7724', 301);
route::redirect('/woa/blog/5900', '/woa/knowhow/infomation/5900', 301);
route::redirect('/woa/blog/judo_teleentry', '/woa/knowhow/infomation/5900', 301);
route::redirect('/woa/blog/interviewing_telephone/', '/woa/knowhow/infomation/5900', 301);
route::redirect('/woa/blog/5965', '/woa/knowhow/infomation/5965', 301);
route::redirect('/woa/blog/judo_ngcall', '/woa/knowhow/infomation/5965', 301);
route::redirect('/woa/blog/interviewing_ng/', '/woa/knowhow/infomation/5965', 301);
route::redirect('/woa/blog/7567', '/woa/knowhow/infomation/7567', 301);
route::redirect('/woa/blogcat/mensetsu/', '/woa/knowhow/interview', 301);
route::redirect('/woa/blog/4302', '/woa/knowhow/interview/4302', 301);
route::redirect('/woa/blog/judo_interviewing1/', '/woa/knowhow/interview/4302', 301);
route::redirect('/woa/blog/4918', '/woa/knowhow/interview/4918', 301);
route::redirect('/woa/blog/4920', '/woa/knowhow/interview/4920', 301);
route::redirect('/woa/blog/5342', '/woa/knowhow/interview/5342', 301);
route::redirect('/woa/blog/judo_start', '/woa/knowhow/interview/5342', 301);
route::redirect('/woa/blog/5492', '/woa/knowhow/interview/5492', 301);
route::redirect('/woa/blog/interviewing2/', '/woa/knowhow/interview/5492', 301);
route::redirect('/woa/blog/judo_interviewing2', '/woa/knowhow/interview/5492', 301);
route::redirect('/woa/blog/5611', '/woa/knowhow/interview/5611', 301);
route::redirect('/woa/blog/judoseifuku_job_nohunting/', '/woa/knowhow/interview/5611', 301);
route::redirect('/woa/blog/6804', '/woa/knowhow/interview/6804', 301);
route::redirect('/woa/blog/judo_interviewing', '/woa/knowhow/interview/6804', 301);
route::redirect('/woa/blog/judoseifuku_interviewing', '/woa/knowhow/interview/6804', 301);
route::redirect('/woa/blog/6876', '/woa/knowhow/interview/6876', 301);
route::redirect('/woa/blog/interviewing_preparation', '/woa/knowhow/interview/6876', 301);
route::redirect('/woa/blog/6896', '/woa/knowhow/interview/6896', 301);
route::redirect('/woa/blog/mendan_manners', '/woa/knowhow/interview/6896', 301);
route::redirect('/woa/blog/6928', '/woa/knowhow/interview/6928', 301);
route::redirect('/woa/blog/judo_question', '/woa/knowhow/interview/6928', 301);
route::redirect('/woa/blog/6948', '/woa/knowhow/interview/6948', 301);
route::redirect('/woa/blog/judo_howtotell', '/woa/knowhow/interview/6948', 301);
route::redirect('/woa/blog/mendan_selfpr_howtotell', '/woa/knowhow/interview/6948', 301);
route::redirect('/woa/blog/6958', '/woa/knowhow/interview/6958', 301);
route::redirect('/woa/blog/interviewing_finalstage', '/woa/knowhow/interview/6958', 301);
route::redirect('/woa/blog/7042', '/woa/knowhow/interview/7042', 301);
route::redirect('/woa/blog/judo_mensetsugohatto', '/woa/knowhow/interview/7042', 301);
route::redirect('/woa/blog/mensetsu-gohatto', '/woa/knowhow/interview/7042', 301);
route::redirect('/woa/blog/7048', '/woa/knowhow/interview/7048', 301);
route::redirect('/woa/blog/mennsetsu-shibodoki', '/woa/knowhow/interview/7048', 301);
route::redirect('/woa/blog/judo_mennsetsushibodoki', '/woa/knowhow/interview/7048', 301);
route::redirect('/woa/blog/7057', '/woa/knowhow/interview/7057', 301);
route::redirect('/woa/blog/mennsetsu-point', '/woa/knowhow/interview/7057', 301);
route::redirect('/woa/blog/7084', '/woa/knowhow/interview/7084', 301);
route::redirect('/woa/blog/mennsetsu-oreijo', '/woa/knowhow/interview/7084', 301);
route::redirect('/woa/blog/judo_oreijo', '/woa/knowhow/interview/7084', 301);
route::redirect('/woa/blog/7691', '/woa/knowhow/interview/7691', 301);
route::redirect('/woa/blog/judo_interview', '/woa/knowhow/interview/7691', 301);
route::redirect('/woa/blog/5320', '/woa/knowhow/jobdescription/5320', 301);
route::redirect('/woa/blog/judo_job', '/woa/knowhow/jobdescription/5320', 301);
route::redirect('/woa/blog/5340', '/woa/knowhow/jobdescription/5340', 301);
route::redirect('/woa/blog/judo_post', '/woa/knowhow/jobdescription/5340', 301);
route::redirect('/woa/blog/judoseihuku_job/', '/woa/knowhow/jobdescription/5340', 301);
route::redirect('/woa/blog/judoseihuku_post/', '/woa/knowhow/jobdescription/5340', 301);
route::redirect('/woa/blogcat/就職活動（転職活動）/', '/woa/knowhow/jobhunting', 301);
route::redirect('/woa/blog/4433', '/woa/knowhow/jobhunting/4433', 301);
route::redirect('/woa/blog/judo_mendan1/', '/woa/knowhow/jobhunting/4433', 301);
route::redirect('/woa/blog/4740', '/woa/knowhow/jobhunting/4740', 301);
route::redirect('/woa/blog/mendan7/', '/woa/knowhow/jobhunting/4740', 301);
route::redirect('/woa/blog/5046', '/woa/knowhow/jobhunting/5046', 301);
route::redirect('/woa/blog/kengaku2014/', '/woa/knowhow/jobhunting/5046', 301);
route::redirect('/woa/blog/judo_kengaku2014', '/woa/knowhow/jobhunting/5046', 301);
route::redirect('/woa/blog/5126', '/woa/knowhow/jobhunting/5126', 301);
route::redirect('/woa/blog/5329', '/woa/knowhow/jobhunting/5329', 301);
route::redirect('/woa/blog/judoseihuku_role/', '/woa/knowhow/jobhunting/5329', 301);
route::redirect('/woa/blog/judo_role', '/woa/knowhow/jobhunting/5329', 301);
route::redirect('/woa/blog/6782', '/woa/knowhow/jobhunting/6782', 301);
route::redirect('/woa/blog/acupuncturist_jobhunting', '/woa/knowhow/jobhunting/6782', 301);
route::redirect('/woa/blog/hari_success', '/woa/knowhow/jobhunting/6782', 301);
route::redirect('/woa/blog/7705', '/woa/knowhow/jobhunting/7705', 301);
route::redirect('/woa/blog/4527', '/woa/knowhow/jobhuntingqa/4527', 301);
route::redirect('/woa/blog/4602', '/woa/knowhow/jobhuntingqa/4602', 301);
route::redirect('/woa/blog/mendan3/', '/woa/knowhow/jobhuntingqa/4602', 301);
route::redirect('/woa/blog/4623', '/woa/knowhow/jobhuntingqa/4623', 301);
route::redirect('/woa/blog/judoseifuku_opening/', '/woa/knowhow/jobhuntingqa/4623', 301);
route::redirect('/woa/blog/judoseifuku_start/', '/woa/knowhow/jobhuntingqa/4623', 301);
route::redirect('/woa/blog/mendan4/', '/woa/knowhow/jobhuntingqa/4623', 301);
route::redirect('/woa/blog/4661', '/woa/knowhow/jobhuntingqa/4661', 301);
route::redirect('/woa/blog/4710', '/woa/knowhow/jobhuntingqa/4710', 301);
route::redirect('/woa/blog/4870', '/woa/knowhow/jobhuntingqa/4870', 301);
route::redirect('/woa/blog/judo_mendan9', '/woa/knowhow/jobhuntingqa/4870', 301);
route::redirect('/woa/blog/mendan9/', '/woa/knowhow/jobhuntingqa/4870', 301);
route::redirect('/woa/blog/4915', '/woa/knowhow/jobhuntingqa/4915', 301);
route::redirect('/woa/blog/4971', '/woa/knowhow/jobhuntingqa/4971', 301);
route::redirect('/woa/blog/5059', '/woa/knowhow/jobhuntingqa/5059', 301);
route::redirect('/woa/blog/judo_mendan12', '/woa/knowhow/jobhuntingqa/5059', 301);
route::redirect('/woa/blog/7673', '/woa/knowhow/jobhuntingqa/7673', 301);
route::redirect('/woa/blog/judo_jobswitch', '/woa/knowhow/jobhuntingqa/7673', 301);
route::redirect('/woa/blog/4305', '/woa/knowhow/joboffer/4305', 301);
route::redirect('/woa/blog/4367', '/woa/knowhow/joboffer/4367', 301);
route::redirect('/woa/blog/judo_visiting1/', '/woa/knowhow/joboffer/4367', 301);
route::redirect('/woa/blog/4425', '/woa/knowhow/joboffer/4425', 301);
route::redirect('/woa/blog/4614', '/woa/knowhow/joboffer/4614', 301);
route::redirect('/woa/blog/4638', '/woa/knowhow/joboffer/4638', 301);
route::redirect('/woa/blog/visiting4/', '/woa/knowhow/joboffer/4638', 301);
route::redirect('/woa/blog/hari_visiting4', '/woa/knowhow/joboffer/4638', 301);
route::redirect('/woa/blog/4665', '/woa/knowhow/joboffer/4665', 301);
route::redirect('/woa/blog/4686', '/woa/knowhow/joboffer/4686', 301);
route::redirect('/woa/blog/4708', '/woa/knowhow/joboffer/4708', 301);
route::redirect('/woa/blog/4742', '/woa/knowhow/joboffer/4742', 301);
route::redirect('/woa/blog/4771', '/woa/knowhow/joboffer/4771', 301);
route::redirect('/woa/blog/judo_visiting9', '/woa/knowhow/joboffer/4771', 301);
route::redirect('/woa/blog/4868', '/woa/knowhow/joboffer/4868', 301);
route::redirect('/woa/blog/judo_visiting10', '/woa/knowhow/joboffer/4868', 301);
route::redirect('/woa/blog/4911', '/woa/knowhow/joboffer/4911', 301);
route::redirect('/woa/blog/judo_visiting11', '/woa/knowhow/joboffer/4911', 301);
route::redirect('/woa/blog/4973', '/woa/knowhow/joboffer/4973', 301);
route::redirect('/woa/blog/5058', '/woa/knowhow/joboffer/5058', 301);
route::redirect('/woa/blog/5124', '/woa/knowhow/joboffer/5124', 301);
route::redirect('/woa/blog/judo_visiting14', '/woa/knowhow/joboffer/5124', 301);
route::redirect('/woa/blog/visiting14/', '/woa/knowhow/joboffer/5124', 301);
route::redirect('/woa/blog/7732', '/woa/knowhow/joboffer/7732', 301);
route::redirect('/woa/blog/7739', '/woa/knowhow/joboffer/7739', 301);
route::redirect('/woa/blog/7745', '/woa/knowhow/joboffer/7745', 301);
route::redirect('/woa/blog/7750', '/woa/knowhow/joboffer/7750', 301);
route::redirect('/woa/blog/bonus', '/woa/knowhow/joboffer/7750', 301);
route::redirect('/woa/blog/7753', '/woa/knowhow/joboffer/7753', 301);
route::redirect('/woa/blog/7757', '/woa/knowhow/joboffer/7757', 301);
route::redirect('/woa/blog/7761', '/woa/knowhow/joboffer/7761', 301);
route::redirect('/woa/blog/7798', '/woa/knowhow/joboffer/7798', 301);
route::redirect('/woa/blog/judo_global_eigo', '/woa/knowhow/joboffer/7798', 301);
route::redirect('/woa/blog/7799', '/woa/knowhow/joboffer/7799', 301);
route::redirect('/woa/blog/7802', '/woa/knowhow/joboffer/7802', 301);
route::redirect('/woa/blog/judo_holiday2', '/woa/knowhow/joboffer/7802', 301);
route::redirect('/woa/blog/7808', '/woa/knowhow/joboffer/7808', 301);
route::redirect('/woa/blog/judo_Sport', '/woa/knowhow/joboffer/7808', 301);
route::redirect('/woa/blog/7815', '/woa/knowhow/joboffer/7815', 301);
route::redirect('/woa/blog/5926', '/woa/knowhow/nationalexamanation/5926', 301);
route::redirect('/woa/blog/judoseifuku_examination_passrate/', '/woa/knowhow/nationalexamanation/5926', 301);
route::redirect('/woa/blog/judo_examinationpassrate', '/woa/knowhow/nationalexamanation/5926', 301);
route::redirect('/woa/blogcat/rirekisho/', '/woa/knowhow/resume', 301);
route::redirect('/woa/blogcat/sibodoki/', '/woa/knowhow/resume', 301);
route::redirect('/woa/blogcat/職務経歴書/', '/woa/knowhow/resume', 301);
route::redirect('/woa/blog/4744', '/woa/knowhow/resume/4744', 301);
route::redirect('/woa/blog/mendan8/', '/woa/knowhow/resume/4744', 301);
route::redirect('/woa/blog/judo_mendan8', '/woa/knowhow/resume/4744', 301);
route::redirect('/woa/blog/5174', '/woa/knowhow/resume/5174', 301);
route::redirect('/woa/blog/hope_example/', '/woa/knowhow/resume/5174', 301);
route::redirect('/woa/blog/judo_hopeexample', '/woa/knowhow/resume/5174', 301);
route::redirect('/woa/blog/resume_example', '/woa/knowhow/resume/5174', 301);
route::redirect('/woa/blog/5290', '/woa/knowhow/resume/5290', 301);
route::redirect('/woa/blog/5290', '/woa/knowhow/resume/5290', 301);
route::redirect('/woa/blog/concrete_examples1/', '/woa/knowhow/resume/5290', 301);
route::redirect('/woa/blog/hope_way/', '/woa/knowhow/resume/5290', 301);
route::redirect('/woa/blog/interview_point', '/woa/knowhow/resume/5290', 301);
route::redirect('/woa/blog/5296', '/woa/knowhow/resume/5296', 301);
route::redirect('/woa/blog/concrete_examples2/', '/woa/knowhow/resume/5296', 301);
route::redirect('/woa/blog/5376', '/woa/knowhow/resume/5376', 301);
route::redirect('/woa/blog/judo_examples2', '/woa/knowhow/resume/5376', 301);
route::redirect('/woa/blog/6641', '/woa/knowhow/resume/6641', 301);
route::redirect('/woa/blog/6809', '/woa/knowhow/resume/6809', 301);
route::redirect('/woa/blog/judoseifuku_resume', '/woa/knowhow/resume/6809', 301);
route::redirect('/woa/blog/6867', '/woa/knowhow/resume/6867', 301);
route::redirect('/woa/blog/specialist_resume_writing_method', '/woa/knowhow/resume/6867', 301);
route::redirect('/woa/blog/6882', '/woa/knowhow/resume/6882', 301);
route::redirect('/woa/blog/hope_way_basic', '/woa/knowhow/resume/6882', 301);
route::redirect('/woa/blog_curriculum_vitae', '/woa/knowhow/resume/6882', 301);
route::redirect('/woa/blog/6892', '/woa/knowhow/resume/6892', 301);
route::redirect('/woa/blog/6904', '/woa/knowhow/resume/6904', 301);
route::redirect('/woa/blog/resume_howtowrite_4point', '/woa/knowhow/resume/6904', 301);
route::redirect('/woa/blog/6919', '/woa/knowhow/resume/6919', 301);
route::redirect('/woa/blog/sibodokisho_sibodoki', '/woa/knowhow/resume/6919', 301);
route::redirect('/woa/blog/hope_way_move_interviewers_heart', '/woa/knowhow/resume/6919', 301);
route::redirect('/woa/blog/6936', '/woa/knowhow/resume/6936', 301);
route::redirect('/woa/blog/selfpr_howtowrite_self_analysis', '/woa/knowhow/resume/6936', 301);
route::redirect('/woa/blog_selfpr_howtowrite_self_analysis', '/woa/knowhow/resume/6936', 301);
route::redirect('/woa/blog/6943', '/woa/knowhow/resume/6943', 301);
route::redirect('/woa/blog/6950', '/woa/knowhow/resume/6950', 301);
route::redirect('/woa/blog/selfpr_howtowrite_recruitment', '/woa/knowhow/resume/6950', 301);
route::redirect('/woa/blog/6955', '/woa/knowhow/resume/6955', 301);
route::redirect('/woa/blog/curriculum_vitae_concrete', '/woa/knowhow/resume/6955', 301);
route::redirect('/woa/blog/志望動機書の書き方＜具体例編＞', '/woa/knowhow/resume/6955', 301);
route::redirect('/woa/blog/curriculum_vitae', '/woa/knowhow/resume/6955', 301);
route::redirect('/woa/blog/hope_way_example', '/woa/knowhow/resume/6955', 301);
route::redirect('/woa/blog/judo_curriculum', '/woa/knowhow/resume/6955', 301);
route::redirect('/woa/blog/6963', '/woa/knowhow/resume/6963', 301);
route::redirect('/woa/blog/futuregoals_and_enthusiasm', '/woa/knowhow/resume/6963', 301);
route::redirect('/woa/blog/7122', '/woa/knowhow/resume/7122', 301);
route::redirect('/woa/blog/selfpr-reibun', '/woa/knowhow/resume/7122', 301);
route::redirect('/woa/blog/judo_selfpr', '/woa/knowhow/resume/7122', 301);
route::redirect('/woa/blog/7715', '/woa/knowhow/retirement/7715', 301);
route::redirect('/woa/blog/judo_retire', '/woa/knowhow/retirement/7715', 301);
route::redirect('/woa/blog/7614', '/woa/knowhow/salary/7614', 301);
route::redirect('/woa/blog/judo_wages', '/woa/knowhow/salary/7614', 301);
route::redirect('/woa/blog/7620', '/woa/knowhow/salary/7620', 301);
route::redirect('/woa/blog/judo_woman', '/woa/knowhow/salary/7620', 301);
route::redirect('/woa/blog/7631', '/woa/knowhow/salary/7631', 301);
route::redirect('/woa/blog/7632', '/woa/knowhow/salary/7632', 301);
route::redirect('/woa/blog/judo_income', '/woa/knowhow/salary/7632', 301);
route::redirect('/woa/blog/7638', '/woa/knowhow/salary/7638', 301);
route::redirect('/woa/blog_judo_salary', '/woa/knowhow/salary/7638', 301);
route::redirect('/woa/blog/7642', '/woa/knowhow/salary/7642', 301);
route::redirect('/woa/blog/7649', '/woa/knowhow/salary/7649', 301);
route::redirect('/woa/blog/judo_trainer', '/woa/knowhow/salary/7649', 301);
route::redirect('/woa/blog/7669', '/woa/knowhow/salary/7669', 301);
route::redirect('/woa/blog/judo_subcontracting', '/woa/knowhow/salary/7669', 301);
route::redirect('/woa/blog/7675', '/woa/knowhow/salary/7675', 301);
route::redirect('/woa/blog/judo_seikeisouba', '/woa/knowhow/salary/7675', 301);
route::redirect('/woa/blog/7824', '/woa/knowhow/salary/7824', 301);
route::redirect('/woa/blog/7826', '/woa/knowhow/salary/7826', 301);
route::redirect('/woa/blog/hari_womanmoney', '/woa/knowhow/salary/7826', 301);
route::redirect('/woa/blog/7832', '/woa/knowhow/salary/7832', 301);
route::redirect('/woa/blog/hari_work', '/woa/knowhow/salary/7832', 301);
route::redirect('/woa/blog/7453', '/woa/knowhow/salary/7840', 301);
route::redirect('/woa/blog/7840', '/woa/knowhow/salary/7840', 301);
route::redirect('/woa/blog/shinkyu_salary', '/woa/knowhow/salary/7840', 301);
route::redirect('/woa/blog/hari_salary', '/woa/knowhow/salary/7840', 301);
route::redirect('/woa/blog/7844', '/woa/knowhow/salary/7844', 301);
route::redirect('/woa/blog/hari_money', '/woa/knowhow/salary/7844', 301);
route::redirect('/woa/blog/7846', '/woa/knowhow/salary/7846', 301);
route::redirect('/woa/blog/hari_trainer', '/woa/knowhow/salary/7846', 301);
route::redirect('/woa/blog/judoseifuku_enter/', '/woa/knowhow/tiryouka/5474', 301);
route::redirect('/woa/blog/5474', '/woa/knowhow/tiryouka/5474', 301);
route::redirect('/woa/blog/5559', '/woa/knowhow/tiryouka/5559', 301);
route::redirect('/woa/blog/correspondence_education/', '/woa/knowhow/tiryouka/5559', 301);
route::redirect('/woa/blog/judo_education', '/woa/knowhow/tiryouka/5559', 301);
route::redirect('/woa/blog/kokushikurohon1', '/woa', 301);
route::redirect('/woa/blog/kokushikurohon_sale', '/woa', 301);
route::redirect('/woa/blog/6864', '/woa', 301);

route::redirect('/woa/blog/visiting7/', '/woa/column/4708', 301);
route::redirect('/woa/blog/judo_visiting7', '/woa/column/4708', 301);
route::redirect('/woa/blog/visiting2/', '/woa/column/4425', 301);
route::redirect('/woa/blog/hari_visiting2/', '/woa/column/4425', 301);
route::redirect('/woa/blog/visiting6/', '/woa/column/4686', 301);
route::redirect('/woa/blog/hari_visiting6', '/woa/column/4686', 301);
route::redirect('/woa/blog/seminar1/', '/woa/column/4762', 301);
route::redirect('/woa/blog/4762', '/woa/column/4762', 301);
route::redirect('/woa/blog/seminar4/', '/woa/column/5831', 301);
route::redirect('/woa/blog/5831', '/woa/column/5831', 301);
route::redirect('/woa/blog/visiting5/', '/woa/column/4665', 301);
route::redirect('/woa/blog/judo_visiting5/', '/woa/column/4665', 301);
route::redirect('/woa/blog/visiting8/', '/woa/column/4742', 301);
route::redirect('/woa/blog/hari_visiting8', '/woa/column/4742', 301);
route::redirect('/woa/blog/visiting3/', '/woa/column/4614', 301);
route::redirect('/woa/blog/judo_visiting3', '/woa/column/4614', 301);
route::redirect('/woa/blog/mendan10/', '/woa/column/4915', 301);
route::redirect('/woa/blog/judo_mendan10', '/woa/column/4915', 301);

route::redirect('/woa/blog/seminar5/', '/woa/seminarnews/1', 301);
route::redirect('/woa/blog/5980', '/woa/seminarnews/1', 301);
route::redirect('/woa/blog/6019', '/woa/seminarnews/1', 301);
route::redirect('/woa/blog/6027', '/woa/seminarnews/1', 301);
route::redirect('/woa/blog/seminar6/', '/woa/seminarnews/1', 301);
route::redirect('/woa/blog/seminar7/', '/woa/seminarnews/1', 301);
route::redirect('/woa/blog/seminar8/', '/woa/seminarnews/1', 301);
route::redirect('/woa/blog/seminar5/', '/woa/seminarnews/1', 301);
route::redirect('/woa/blog/6034', '/woa/seminarnews/1', 301);
route::redirect('/woa/blog/seminar2/', '/woa/seminarnews/1', 301);
route::redirect('/woa/blog/5326', '/woa/seminarnews/1', 301);
route::redirect('/woa/blog/seminar1/', '/woa/seminarnews/1', 301);
route::redirect('/woa/blog/4762', '/woa/seminarnews/1', 301);

route::redirect('/woa/blog/questionnaire51/', '/woa/taikendan/5971', 301);
route::redirect('/woa/blog/questionnaire46/', '/woa/taikendan/5469', 301);
route::redirect('/woa/blog/questionnaire48-2/', '/woa/taikendan/5498', 301);
route::redirect('/woa/blog/questionnaire47/', '/woa/taikendan/5428', 301);
route::redirect('/woa/blog/questionnaire49/', '/woa/taikendan/5739', 301);
route::redirect('/woa/blog/questionnair5/', '/woa/taikendan/4548', 301);
route::redirect('/woa/blog/4906', '/woa/taikendan/4906', 301);
route::redirect('/woa/blog/4672', '/woa/taikendan/4672', 301);
route::redirect('/woa/blog/4625', '/woa/taikendan/4625', 301);
route::redirect('/woa/blog/4674', '/woa/taikendan/4674', 301);
route::redirect('/woa/blog/4738', '/woa/taikendan/4738', 301);
route::redirect('/woa/blog/4568', '/woa/taikendan/4568', 301);
route::redirect('/woa/blog/5971', '/woa/taikendan/5971', 301);
route::redirect('/woa/blog/5040', '/woa/taikendan/5040', 301);
route::redirect('/woa/blog/5469', '/woa/taikendan/5469', 301);
route::redirect('/woa/blog/4736', '/woa/taikendan/4736', 301);
route::redirect('/woa/blog/questionnaire3/', '/woa/taikendan/4497', 301);
route::redirect('/woa/blog/4619', '/woa/taikendan/4619', 301);
route::redirect('/woa/blog/4866', '/woa/taikendan/4866', 301);
route::redirect('/woa/blog/5498', '/woa/taikendan/5498', 301);
route::redirect('/woa/blog/questionnaire44/', '/woa/taikendan/5350', 301);
route::redirect('/woa/blog/5350', '/woa/taikendan/5350', 301);
route::redirect('/woa/blog/4737', '/woa/taikendan/4737', 301);
route::redirect('/woa/blog/questionnair7/', '/woa/taikendan/4563', 301);
route::redirect('/woa/blog/questionnair19/', '/woa/taikendan/4646', 301);
route::redirect('/woa/blog/4646', '/woa/taikendan/4646', 301);
route::redirect('/woa/blog/questionnaire43/', '/woa/taikendan/5337', 301);
route::redirect('/woa/blog/5337', '/woa/taikendan/5337', 301);
route::redirect('/woa/blog/4642', '/woa/taikendan/4642', 301);
route::redirect('/woa/blog/questionnair13/', '/woa/taikendan/4609', 301);
route::redirect('/woa/blog/4609', '/woa/taikendan/4609', 301);
route::redirect('/woa/blog/questionnair29/', '/woa/taikendan/4737', 301);
route::redirect('/woa/blog/4660', '/woa/taikendan/4660', 301);
route::redirect('/woa/blog/4605', '/woa/taikendan/4605', 301);
route::redirect('/woa/blog/4563', '/woa/taikendan/4563', 301);
route::redirect('/woa/blog/questionnair33/', '/woa/taikendan/4872', 301);
route::redirect('/woa/blog/4872', '/woa/taikendan/4872', 301);
route::redirect('/woa/blog/4388', '/woa/taikendan/4388', 301);
route::redirect('/woa/blog/4495', '/woa/taikendan/4495', 301);
route::redirect('/woa/blog/4553', '/woa/taikendan/4553', 301);
route::redirect('/woa/blog/4636', '/woa/taikendan/4636', 301);
route::redirect('/woa/blog/4706', '/woa/taikendan/4706', 301);
route::redirect('/woa/blog/4497', '/woa/taikendan/4497', 301);
route::redirect('/woa/blog/questionnair40/', '/woa/taikendan/5075', 301);
route::redirect('/woa/blog/questionnaire42/', '/woa/taikendan/5331', 301);
route::redirect('/woa/blog/5331', '/woa/taikendan/5331', 301);
route::redirect('/woa/blog/4574', '/woa/taikendan/4574', 301);
route::redirect('/woa/blog/questionnaire41/', '/woa/taikendan/4495', 301);
route::redirect('/woa/blog/questionnair34/', '/woa/taikendan/4875', 301);
route::redirect('/woa/blog/4875', '/woa/taikendan/4875', 301);
route::redirect('/woa/blog/4702', '/woa/taikendan/4702', 301);
route::redirect('/woa/blog/4597', '/woa/taikendan/4597', 301);
route::redirect('/woa/blog/5428', '/woa/taikendan/5428', 301);
route::redirect('/woa/blog/questionnaire50/', '/woa/taikendan/5906', 301);
route::redirect('/woa/blog/5906', '/woa/taikendan/5906', 301);
route::redirect('/woa/blog/4690', '/woa/taikendan/4690', 301);
route::redirect('/woa/blog/5075', '/woa/taikendan/5075', 301);
route::redirect('/woa/blog/5739', '/woa/taikendan/5739', 301);
route::redirect('/woa/blog/4470', '/woa/taikendan/4470', 301);
route::redirect('/woa/blog/4841', '/woa/taikendan/4841', 301);
route::redirect('/woa/blog/4572', '/woa/taikendan/4572', 301);
route::redirect('/woa/blog/4908', '/woa/taikendan/4908', 301);
route::redirect('/woa/blog/questionnair18/', '/woa/taikendan/4645', 301);
route::redirect('/woa/blog/4645', '/woa/taikendan/4645', 301);
route::redirect('/woa/blog/4698', '/woa/taikendan/4698', 301);
route::redirect('/woa/blog/questionnair27/', '/woa/taikendan/4735', 301);
route::redirect('/woa/blog/5038', '/woa/taikendan/5038', 301);
route::redirect('/woa/blog/4735', '/woa/taikendan/4735', 301);
route::redirect('/woa/blog/4905', '/woa/taikendan/4905', 301);
route::redirect('/woa/blog/4512', '/woa/taikendan/4512', 301);
route::redirect('/woa/blog/4548', '/woa/taikendan/4548', 301);
route::redirect('/woa/blog/questionnaire45/', '/woa/taikendan/5381', 301);
route::redirect('/woa/blog/5381', '/woa/taikendan/5381', 301);
route::redirect('/woa/blog/questionnair30/', '/woa/taikendan/4738', 301);

route::redirect('/woa/staff/' . urldecode('%E5%90%89%E5%8E%9F%E6%83%87%E4%B8%80'), '/woa/staff/1', 301);
route::redirect('/woa/staff/' . urldecode('%E5%B2%A9%E7%80%AC%E7%BF%94%E5%B9%B3'), '/woa/staff/2', 301);
route::redirect('/woa/staff/' . urldecode('%E5%A4%A7%E6%A0%B9%E7%94%B0%E8%8A%BD%E8%A1%A3'), '/woa/staff/8', 301);
route::redirect('/woa/staff/' . urldecode('%E8%BF%91%E6%B1%9F%E5%B1%8B%E6%B5%A9%E4%B8%80'), '/woa/staff/9', 301);
route::redirect('/woa/staff/' . urldecode('%E6%A0%97%E5%B3%B6%E6%8B%93%E4%B9%9F'), '/woa/staff/10', 301);
route::redirect('/woa/staff/' . urldecode('%E5%B6%8B%E5%80%89%E5%81%A5%E6%99%83'), '/woa/staff/11', 301);
route::redirect('/woa/staff/' . urldecode('%E4%B8%AD%E5%B1%B1%E8%8C%9C'), '/woa/staff/12', 301);
route::redirect('/woa/staff/' . urldecode('%E4%BB%8A%E5%B7%9D%E8%88%AA'), '/woa/staff/13', 301);
route::redirect('/woa/staff/' . urldecode('%E5%B1%B1%E7%94%B0%E9%99%BD'), '/woa/staff/14', 301);
route::redirect('/woa/staff/' . urldecode('%E6%9C%8D%E9%83%A8%E5%B8%8C%E7%BE%8E'), '/woa/staff/15', 301);
route::redirect('/woa/staff/' . urldecode('%E5%90%89%E7%94%B0%E7%BF%94'), '/woa/staff/18', 301);
route::redirect('/woa/staff/' . urldecode('%E5%B1%B1%E5%8F%A3%E6%95%AC%E5%A4%AA'), '/woa/staff/25', 301);
route::redirect('/woa/staff/' . urldecode('%E6%A9%8B%E6%9C%AC%E4%B8%80%E6%A8%B9'), '/woa/staff/26', 301);
route::redirect('/woa/staff/' . urldecode('%E6%B0%B8%E9%87%8E%E6%AF%AC%E5%A5%88'), '/woa/staff/28', 301);
route::redirect('/woa/staff/' . urldecode('%E9%B3%A5%E6%B5%B7%E9%81%94%E4%B9%9F'), '/woa/staff/30', 301);
route::redirect('/woa/staff/' . urldecode('%E7%9B%B8%E5%86%85%E5%85%89'), '/woa/staff/31', 301);
route::redirect('/woa/staff/' . urldecode('%E9%88%B4%E6%9C%A8%E6%95%A6%E4%B9%9F'), '/woa/staff/33', 301);
route::redirect('/woa/staff/' . urldecode('%E5%B0%8F%E5%AE%AE%E8%88%9E%E5%AD%90'), '/woa/staff/34', 301);
route::redirect('/woa/staff/' . urldecode('%E4%BD%90%E9%87%8E%E7%BE%8E%E5%92%B2'), '/woa/staff/35', 301);
route::redirect('/woa/staff/' . urldecode('%E9%82%91%E6%A9%8B%E6%B5%A9%E5%B9%B3'), '/woa/staff/36', 301);

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('TopFromOld');
Route::get('/woa', [\App\Http\Controllers\HomeController::class, 'index'])->name('Top');

Route::post('/woa/redirect', [\App\Http\Controllers\JobController::class, 'redirect'])->name('Redirect');

Route::match(['get', 'post'], '/woa/search/{page?}', [\App\Http\Controllers\JobController::class, 'jobsearch'])->name('JobSearch')->where('page', '[0-9]+');
Route::match(['get', 'post'], '/woa/new/{page?}', [\App\Http\Controllers\JobController::class, 'jobsearch'])->name('NewList')->where('page', '[0-9]+');
Route::match(['get', 'post'], '/woa/blankok/{page?}', [\App\Http\Controllers\JobController::class, 'jobsearch'])->name('BlankList')->where('page', '[0-9]+');
Route::match(['get', 'post'], '/woa/area/{pref}/new/{page?}', [\App\Http\Controllers\JobController::class, 'areanewlist'])->name('AreaNewList')->where('page', '[0-9]+');
Route::match(['get', 'post'], '/woa/area/{pref}/blank/{page?}', [\App\Http\Controllers\JobController::class, 'areanewlist'])->name('AreaBlankList')->where('page', '[0-9]+');
Route::match(['get', 'post'], '/woa/job/{type}/new/{page?}', [\App\Http\Controllers\JobController::class, 'jobnewlist'])->name('JobNewList')->where('page', '[0-9]+');
Route::match(['get', 'post'], '/woa/job/{type}/blank/{page?}', [\App\Http\Controllers\JobController::class, 'jobnewlist'])->name('JobBlankList')->where('page', '[0-9]+');

Route::get('/woa/job/{id}/{pref}/{searchKey}_{searchValue}/{page?}', [\App\Http\Controllers\JobController::class, 'byoption'])->name('JobAreaSelectSearch')->where(['searchKey' => '(employ|business)', 'searchValue' => '[a-z0-9]+', 'page' => '[0-9]+']);
Route::get('/woa/job/{id}/{pref}/{state}/{searchKey}_{searchValue}/{page?}', [\App\Http\Controllers\JobController::class, 'byoptionstate'])->name('JobAreaStateSelectSearch')->where(['searchKey' => '(employ|business)', 'searchValue' => '[a-z0-9]+', 'page' => '[0-9]+']);

Route::get('/woa/area/{pref}/{state}/{page?}', [\App\Http\Controllers\JobController::class, 'areastateselect'])->name('AreaStateSelect')->where('page', '[0-9]+');
Route::get('/woa/area/{pref}/{state}/ekichika5/{page?}', [\App\Http\Controllers\JobController::class, 'areastateselect'])->name('AreaStateSelectEkichika5')->where('page', '[0-9]+');
Route::get('/woa/area/{id}', [\App\Http\Controllers\JobController::class, 'areaselect'])->name('AreaSelect');
Route::get('/woa/area', [\App\Http\Controllers\JobController::class, 'area'])->name('Area');
Route::get('/woa/job', [\App\Http\Controllers\JobController::class, 'type'])->name('Job');
Route::get('/woa/job/{id}', [\App\Http\Controllers\JobController::class, 'typeselect'])->name('JobSelect');
Route::get('/woa/job/{id}/{pref}/{page?}', [\App\Http\Controllers\JobController::class, 'jobareaselect'])->name('JobAreaSelect')->where('page', '[0-9]+');
Route::get('/woa/job/{id}/{pref}/ekichika5/{page?}', [\App\Http\Controllers\JobController::class, 'jobareaselect'])->name('JobAreaSelectEkichika5')->where('page', '[0-9]+');
Route::get('/woa/job/{id}/{pref}/{state}/{page?}', [\App\Http\Controllers\JobController::class, 'jobareastateselect'])->name('JobAreaStateSelect')->where('page', '[0-9]+');
Route::get('/woa/job/{id}/{pref}/{state}/ekichika5/{page?}', [\App\Http\Controllers\JobController::class, 'jobareastateselect'])->name('JobAreaStateSelectEkichika5')->where('page', '[0-9]+');

// 求人詳細（新テーブル woa_opportunity）
Route::get('/woa/detail/{id}.html', [\App\Http\Controllers\JobDetailController::class, 'index'])->name('OpportunityDetail');

// 求人詳細（旧テーブル job）
Route::get('/woa/detail/{id}', [\App\Http\Controllers\JobController::class, 'jobdetail'])->name('JobDetail');

Route::get('/woa/company/{id}', [\App\Http\Controllers\CompanyController::class, 'detail'])->name('CompanyList')->where(['id' => '[0-9]+']);

// コラム関連
Route::get('/woa/blog/{id}', [\App\Http\Controllers\BlogController::class, 'detail'])->name('BlogDetail');

// 解答速報
Route::get('/woa/kaitousokuhou', [\App\Http\Controllers\KaitouController::class, 'index'])->name('Kaitou');
Route::get('/woa/kaitousokuhou/kako', [\App\Http\Controllers\KaitouController::class, 'kako'])->name('KaitouKako');
Route::get('/woa/kaitousokuhou/{id}', [\App\Http\Controllers\KaitouController::class, 'detail'])->name('KaitouDetail');

Route::get('/woa/taikendan', [\App\Http\Controllers\ExperienceController::class, 'index'])->name('Tensyoku');
Route::get('/woa/taikendan/list/{page?}', [\App\Http\Controllers\ExperienceController::class, 'list'])->name('TensyokuList')->where('page', '[0-9]+');
Route::get('/woa/taikendan/cp_list', [\App\Http\Controllers\ExperienceController::class, 'cpList'])->name('TensyokuCpList');
Route::get('/woa/taikendan/category/{category}/{page?}', [\App\Http\Controllers\ExperienceController::class, 'category'])->name('TensyokuCategory')->where('page', '[0-9]+');
Route::get('/woa/taikendan/{id}', [\App\Http\Controllers\ExperienceController::class, 'detail'])->name('TensyokuDetail');

Route::get('/woa/knowhow', [\App\Http\Controllers\KnowhowController::class, 'index'])->name('Knowhow');
Route::get('/woa/knowhow/{knowhow}', [\App\Http\Controllers\KnowhowController::class, 'list'])->name('KnowhowList');
Route::get('/woa/knowhow/{knowhow}/{id}', [\App\Http\Controllers\KnowhowController::class, 'detail'])->name('KnowhowDetail');

// スタッフ関連
Route::group(['prefix' => '/woa/staff'], function () {
    Route::get('/', [\App\Http\Controllers\StaffController::class, 'list'])->name('StaffList');
    Route::get('/{id}', [\App\Http\Controllers\StaffController::class, 'detail'])->name('StaffDetail')->where(['id' => '[0-9]+']);
    Route::get('/{staffId}/case{caseNo}', [\App\Http\Controllers\StaffController::class, 'example'])->name('StaffExample')->where(['staffId' => '[0-9]+', 'caseNo' => '[0-9]+']);
});

Route::get('/woa/guide', [\App\Http\Controllers\StaticController::class, 'guideindex'])->name('Guide');
Route::get('/woa/rule', [\App\Http\Controllers\StaticController::class, 'rule'])->name('Rule');
Route::get('/woa/privacy', [\App\Http\Controllers\StaticController::class, 'privacy'])->name('Privacy');

// 機能訓練指導員のインタビューページ
Route::get('/woa/interview-fti-1', [\App\Http\Controllers\StaticController::class, 'interviewFti1'])->name('InterviewFti1');
Route::get('/woa/interview-fti-2', [\App\Http\Controllers\StaticController::class, 'interviewFti2'])->name('InterviewFti2');

//静的ページ
Route::get('/woa/include/ct/{label}.html', [\App\Http\Controllers\PageController::class, 'ctIndex'])->name('ctIndex');

Route::get('/woa/service', [\App\Http\Controllers\ServiceController::class, 'service'])->name('Service');
Route::get('/woa/service/free', [\App\Http\Controllers\ServiceController::class, 'servicefree'])->name('ServiceFree');
Route::get('/woa/service/feature', [\App\Http\Controllers\ServiceController::class, 'servicefeature'])->name('ServiceFeature');
Route::get('/woa/service/find', [\App\Http\Controllers\ServiceController::class, 'servicefind'])->name('ServiceFind');

Route::get('/woa/recruit', [\App\Http\Controllers\StaticController::class, 'recruit'])->name('Recruit');
Route::post('/woa/recruitcomp', [\App\Http\Controllers\StaticController::class, 'recruitcomp'])->name('RecruitComp');
Route::get('/woa/recommended', [\App\Http\Controllers\StaticController::class, 'recommended'])->name('Recommended');
Route::get('/woa/contact', [\App\Http\Controllers\StaticController::class, 'contact'])->name('Contact');
Route::post('/woa/contactcomp', [\App\Http\Controllers\StaticController::class, 'contactcomp'])->name('ContactComp');

Route::get('/woa/stateget', [\App\Http\Controllers\HomeController::class, 'stateget'])->name('Stateget');

Route::get('/woa/access', [\App\Http\Controllers\StaticController::class, 'access'])->name('Access');

// 管理画面
Route::get('/woa/admin', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('AdminHome');
// ユーザ管理
Route::get('/woa/admin/user', [\App\Http\Controllers\Admin\AdminController::class, 'user'])->name('AdminUser');
Route::get('/woa/admin/userdelete/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'userdelete'])->name('AdminUserDelete');
Route::get('/woa/admin/register', [\App\Http\Controllers\Admin\LoginController::class, 'register'])->name('Register');
Route::post('/woa/admin/registerinsert', [\App\Http\Controllers\Admin\LoginController::class, 'registerinsert'])->name('RegisterInsert');
// ログイン・ログアウト
Route::get('/woa/admin/login', [\App\Http\Controllers\Admin\LoginController::class, 'index'])->name('Login');
Route::post('/woa/admin/logincheck', [\App\Http\Controllers\Admin\LoginController::class, 'logincheck'])->name('LoginCheck');
Route::get('/woa/admin/logout', [\App\Http\Controllers\Admin\LoginController::class, 'logout'])->name('Logout');
// 就職活動ノウハウブログ一覧
Route::get('/woa/admin/syusyokubloglist', [\App\Http\Controllers\Admin\BlogController::class, 'syusyokulist'])->name('SyusyokuBlogList');
Route::get('/woa/admin/syusyokublogdetail/{id}', [\App\Http\Controllers\Admin\BlogController::class, 'syusyokudetail'])->name('SyusyokuBlogDetail');
Route::post('/woa/admin/syusyokuupdate', [\App\Http\Controllers\Admin\BlogController::class, 'syusyokuupdate'])->name('SyusyokuBlogUpdate');
Route::get('/woa/admin/syusyokublognew', [\App\Http\Controllers\Admin\BlogController::class, 'syusyokunew'])->name('SyusyokuBlogNew');
Route::get('/woa/admin/syusyokublogdel/{id}', [\App\Http\Controllers\Admin\BlogController::class, 'syusyokudel'])->name('SyusyokuBlogDel');
// 画像一覧（ブログ用）
Route::get('/woa/admin/blogimage', [\App\Http\Controllers\Admin\BlogController::class, 'blogimage'])->name('BlogImage');
Route::post('/woa/admin/blogimageupload', [\App\Http\Controllers\Admin\BlogController::class, 'blogimageupload'])->name('BlogImageUpload');
// 解答速報ページ管理
Route::post('/woa/admin/kaitouupdate', [\App\Http\Controllers\Admin\AdminController::class, 'kaitouupdate'])->name('AdminKaitouUpdate');
Route::get('/woa/admin/kaitou', [\App\Http\Controllers\Admin\AdminController::class, 'kaitou'])->name('AdminKaitou');
Route::get('/woa/admin/kaitou/detail/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'kaitoudetail'])->name('AdminKaitouDetail');
Route::get('/woa/admin/kaitounew', [\App\Http\Controllers\Admin\AdminController::class, 'kaitounew'])->name('AdminKaitouNew');
Route::get('/woa/admin/kaitoudel/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'kaitoudel'])->name('AdminKaitouDel');
// 解答速報画像管理
Route::get('/woa/admin/kaitou/image', [\App\Http\Controllers\Admin\AdminController::class, 'image'])->name('KaitouImage');
Route::post('/woa/admin/kaitou/imageupload', [\App\Http\Controllers\Admin\AdminController::class, 'imageupload'])->name('KaitouImageUpload');
// 解答速報変換
Route::get('/woa/admin/answer', [\App\Http\Controllers\Admin\AdminController::class, 'answer'])->name('answer');
Route::post('/woa/admin/upload-json-answer-no', [\App\Http\Controllers\Admin\CsvController::class, 'downloadAnswerNoJson'])->name('answerNo');
Route::post('/woa/admin/upload-json-answer', [\App\Http\Controllers\Admin\CsvController::class, 'downloadAnswerJson'])->name('answerAll');
// hubspot
Route::get('/woa/admin/hubspot/auth', [\App\Http\Controllers\Admin\HubSpotController::class, 'auth'])->name('HubSpot');
Route::get('/woa/admin/hubspot/callback', [\App\Http\Controllers\Admin\HubSpotController::class, 'callback'])->name('HubSpotCallback');

// スタッフ関連ADMIN
Route::get('/woa/admin/stafflist', [\App\Http\Controllers\Admin\AdminController::class, 'stafflist'])->name('AdminStaffList');
Route::get('/woa/admin/staffnew', [\App\Http\Controllers\Admin\AdminController::class, 'staffnew'])->name('AdminStaffNew');
Route::get('/woa/admin/staffupdate/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'staffupdate'])->name('AdminStaffUpdate')->where('id', '[0-9]+');
Route::get('/woa/admin/staffdel/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'staffdelete'])->name('AdminStaffDel')->where('id', '[0-9]+');
Route::post('/woa/admin/staffpost', [\App\Http\Controllers\Admin\AdminController::class, 'staffpost'])->name('AdminStaffPost');
// スタッフ関連ADMIN(staff機能が増える場合はAdminController肥大化防ぐためにこちらに記述する)
Route::group(['prefix' => '/woa/admin/staff', 'as' => 'staff.'], function() {
    Route::get('/example/select/{exampleId}', [\App\Http\Controllers\Admin\StaffController::class, 'exampleSelect'])->name('example.select');
    Route::post('/example/upsert/{id}/{exampleId?}', [\App\Http\Controllers\Admin\StaffController::class, 'exampleUpsert'])->name('example.upsert');
    Route::get('/example/delete/{exampleId}', [\App\Http\Controllers\Admin\StaffController::class, 'exampleDelete'])->name('example.delete');
});

// 黒本リスト
Route::get('/woa/admin/job/kurohon', [\App\Http\Controllers\Admin\JobController::class, 'kurohonOperation'])->name('Kurohon');
Route::get('/woa/admin/job/kurohonListDownload', [\App\Http\Controllers\Admin\CsvController::class, 'kurohonListDownload'])->name('KurohonListCsvDownload');
Route::post('/woa/admin/job/kurohonListUpload', [\App\Http\Controllers\Admin\CsvController::class, 'kurohonListUpload'])->name('KurohonListCsvUpload');

// 注目枠求人管理
Route::get('/woa/admin/propportunity', [\App\Http\Controllers\Admin\PrOpportunityController::class, 'index'])->name('PrOpportunity');
Route::post('/woa/admin/propportunity/upload', [\App\Http\Controllers\Admin\PrOpportunityController::class, 'upload'])->name('PrOpportunityListCsvUpload');
Route::get('/woa/admin/propportunity/detail/{id}', [\App\Http\Controllers\Admin\PrOpportunityController::class, 'detail'])->name('PrOpportunityDetail')->where('id', '[0-9]+');
Route::get('/woa/admin/propportunity/delete/{id}', [\App\Http\Controllers\Admin\PrOpportunityController::class, 'delete'])->name('PrOpportunityDelete')->where('id', '[0-9]+');
Route::post('/woa/admin/propportunity/update', [\App\Http\Controllers\Admin\PrOpportunityController::class, 'update'])->name('PrOpportunityUpdate');

// 求職者登録
Route::get('/entry/{type}_{t}', [\App\Http\Controllers\Entry\EntryController::class, 'index'])->name('EntryFromOld')->where(['type' => 'PC|SP', 't' => '[0-9]+']);
Route::post('/entry/fin.html', [\App\Http\Controllers\Entry\EntryController::class, 'fin'])->name('EntryFinFromOld');
Route::get('/woa/entry/{type}_{t}', [\App\Http\Controllers\Entry\EntryController::class, 'index'])->name('Entry')->where(['type' => 'PC|SP', 't' => '[0-9]+']);
Route::post('/woa/entry/fin.html', [\App\Http\Controllers\Entry\EntryController::class, 'fin'])->name('EntryFin');
// 求職者登録GLP
Route::match(['get', 'post'], '/glp/{lpId}', [\App\Http\Controllers\Entry\GlpController::class, 'index'])->name('GlpFromOld')->where(['lpId' => '[a-zA-Z0-9_\-]+']);
Route::match(['get', 'post'], '/woa/glp/{lpId}', [\App\Http\Controllers\Entry\GlpController::class, 'index'])->name('Glp')->where(['lpId' => '[a-zA-Z0-9_\-]+']);

// 採用担当問合せ
Route::get('/woa/empinquiry', [\App\Http\Controllers\Company\EntryController::class, 'index']);
Route::post('/woa/empinquiry/comp', [\App\Http\Controllers\Company\EntryController::class, 'fin']);
Route::post('/woa/empinquiry/optout', [\App\Http\Controllers\Company\EntryController::class, 'stop']);

// 求職者登録API
Route::post('/api/regist', [\App\Http\Controllers\Api\EntryApiController::class, 'post']);

// Ajax API
Route::post('/woa/api/{type}', [\App\Http\Controllers\Api\ApiController::class, 'post']);

// エリアの求人数取得API
Route::get('woa/api/getAreaOrderCount', [\App\Http\Controllers\Api\ApiController::class, 'getAreaOrderCount']);
// 掘起しの連絡希望時間帯登録API
Route::get('woa/api/reRegistReContactTime', [\App\Http\Controllers\Api\ApiController::class, 'reRegistReContactTime']);
// 詳細画面に表示する掘起し登録API
Route::post('woa/api/modalDigs/regist', [\App\Http\Controllers\Api\ApiController::class, 'modalDigsRegist'])->name('api.modalDigs.regist');

// 404
Route::match(['get', 'post'], '/woa/about', [\App\Http\Controllers\StaticController::class, 'notfound'])->name('About');
Route::match(['get', 'post'], '/woa/column', [\App\Http\Controllers\StaticController::class, 'notfound'])->name('About');
Route::match(['get', 'post'], '/woa/seminar', [\App\Http\Controllers\StaticController::class, 'notfound'])->name('About');
Route::match(['get', 'post'], '/woa/sitemap', [\App\Http\Controllers\StaticController::class, 'notfound'])->name('Sitemap');
Route::match(['get', 'post'], '/woa/sp/sitemap/', [\App\Http\Controllers\StaticController::class, 'notfound'])->name('Sitemap');

// 掘起し
Route::group(['prefix' => '/woa/re', 'as' => 're.'], function() {
    Route::get('/', [\App\Http\Controllers\Reentry\ReentryController::class, 'index'])->name('t1'); // t1
    Route::get('/time', [\App\Http\Controllers\Reentry\ReentryController::class, 'index'])->name('t6'); // t6
});
Route::group(['prefix' => '/woa/contact', 'as' => 're.'], function() {
    Route::get('/time', [\App\Http\Controllers\Reentry\ReentryController::class, 'index'])->name('t2'); // t2
    Route::get('/kisotsu-date-and-time', [\App\Http\Controllers\Reentry\ReentryController::class, 'index'])->name('t3'); // 掘り起しフォーム
    Route::get('/kisotsu-time', [\App\Http\Controllers\Reentry\ReentryController::class, 'index'])->name('t4'); // 既卒向けに問い合わせ
    Route::get('/shukatu-concierge', [\App\Http\Controllers\Reentry\ReentryController::class, 'index'])->name('t5'); // 新卒向け掘り起し
});
Route::post('/woa/reentryfin', [\App\Http\Controllers\Reentry\ReentryController::class, 'fin']);
Route::post('/woa/jobentryfin', [\App\Http\Controllers\Jobentry\JobentryController::class, 'fin']);

// 黒本リスト
Route::get('/woa/re/kurohon', [\App\Http\Controllers\Kurohon\KurohonLpController::class, 'index']);
Route::post('/woa/re/kurohon/complete', [\App\Http\Controllers\Kurohon\KurohonLpController::class, 'fin']);

// feed
Route::get('/detail/{feed}/{id}/job_type_{jobType}/employ_id_{employId}/station_{station}/sec_{sec}', [\App\Http\Controllers\Recruit\FeedController::class, 'station'])
    ->name('FeedController.station')
    ->where([
        'feed'     => 'indeed|kyujinbox|stanby|jobda',
        'id'       => '[A-Za-z0-9_]+',
        'jobType'  => '[0-9]+',
        'employId' => '[0-9]+',
        'station'  => '[0-3]+',
        'sec'      => '[0-1]',
    ]);
Route::get('/detail/{feed}/{id}/job_type_{jobType}/employ_id_{employId}/sec_{sec}', [\App\Http\Controllers\Recruit\FeedController::class, 'index'])
    ->name('FeedController.index')
    ->where([
        'feed'     => 'indeed|kyujinbox|stanby|jobda',
        'id'       => '[A-Za-z0-9_]+',
        'jobType'  => '[0-9]+',
        'employId' => '[0-9]+',
        'sec'      => '[0-1]',
    ]);

Route::get('/detail/{feed}/{id}.html', [\App\Http\Controllers\Recruit\FeedController::class, 'changeTransitionUrl'])
    ->where([
        'feed' => 'indeed|kyujinbox|stanby|jobda',
        'id'   => '[A-Za-z0-9_]+',
    ]
    );

// kyujinboxのみPOSTメソッドを許可する
Route::post('/detail/{feed}/{id}/job_type_{jobType}/employ_id_{employId}/station_{station}/sec_{sec}', [\App\Http\Controllers\Recruit\FeedController::class, 'station'])
    ->name('FeedController.station')
    ->where([
        'feed'     => 'kyujinbox',
        'id'       => '[A-Za-z0-9_]+',
        'jobType'  => '[0-9]+',
        'employId' => '[0-9]+',
        'station'  => '[0-3]+',
        'sec'      => '[0-1]',
    ]);

Route::post('/detail/{feed}/{id}/job_type_{jobType}/employ_id_{employId}/sec_{sec}', [\App\Http\Controllers\Recruit\FeedController::class, 'index'])
    ->name('FeedController.index')
    ->where([
        'feed'     => 'kyujinbox',
        'id'       => '[A-Za-z0-9_]+',
        'jobType'  => '[0-9]+',
        'employId' => '[0-9]+',
        'sec'      => '[0-1]',
    ]);

Route::post('/detail/{feed}/{id}.html', [\App\Http\Controllers\Recruit\FeedController::class, 'changeTransitionUrl'])
    ->where([
        'feed' => 'kyujinbox',
        'id'   => '[A-Za-z0-9_]+',
    ]
    );

// 大阪府特集ページ
Route::get('/woa/osaka2020', [\App\Http\Controllers\StaticController::class, 'osaka2020']);

// LP宣伝ページ
Route::get('/woa/special/job-change-agent', [\App\Http\Controllers\StaticController::class, 'jobchangeagent']);

// 新卒向けの求職者IDページ
Route::get('/woa/special/find-work-agent', [\App\Http\Controllers\StaticController::class, 'findworkagent']);

// 友人紹介者向け
Route::get('/woa/introduce', [\App\Http\Controllers\StaticController::class, 'introduce']);
