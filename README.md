#yii-vote
基于yii2框架下的微信投票后台
    支持项目类型二级联动，多项目批量创建，多图上传。后台设置票数限制及IP投票限制
import.sql为数据库文件

注释信息不完整，后台图片连续展示功能暂缺

api中siteController 展示了用户所需微信的三种认证方式(微信不授权，微信授权及小程序授权)

models中
    Events为投票活动
    Classet为投票活动中的图片细分类型
    Opus为投票活动中的参赛项目
    Profile为用户的基础信息
    Log为投票明细
    User为用户认证表