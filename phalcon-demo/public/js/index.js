$(function(){
    //echarts每日活跃数
    var dom = document.getElementById("echarts_date");
    var myChart = echarts.init(dom);
    var app = {};
    option = null;
    option = {
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            }
        },
        legend: {
            data: ['安卓（Andriod）每日活跃数', '苹果（Ios）每日活跃数']
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        yAxis: {
            type: 'value',
            boundaryGap: [0, 0.01]
        },
        xAxis: {
            type: 'category',
            data: ['2018-12-1','2018-12-2','2018-12-3','2018-12-4','2018-12-5','2018-12-6','2018-12-7']
        },
        series: [
            {
                name: '安卓（Andriod）每日活跃数',
                type: 'bar',
                data: [182303, 234829, 293034, 104970, 131744, 630230, 630230]
            },
            {
                name: '苹果（Ios）每日活跃数',
                type: 'bar',
                data: [192325, 234338, 310020, 121594, 134141, 681807, 784412]
            }
        ]
    };
    if (option && typeof option === "object") {
        myChart.setOption(option, true);
    }

//echarts每月用户数
    var dom = document.getElementById("echarts_box");
    var myChart = echarts.init(dom);
    var app = {};
    option = null;
    var colors = ['#5793f3', '#d14a61'];


    option = {
        color: colors,

        tooltip: {
            trigger: 'none',
            axisPointer: {
                type: 'cross'
            }
        },
        legend: {
            data:['安卓（Andriod）每月用户数', '苹果（Ios）每月用户数']
        },
        grid: {
            top: 70,
            bottom: 50
        },
        xAxis: [
            {
                type: 'category',
                axisTick: {
                    alignWithLabel: true
                },
                axisLine: {
                    onZero: false,
                    lineStyle: {
                        color: colors[1]
                    }
                },
                axisPointer: {
                    label: {
                        formatter: function (params) {
                            return '每月用户数  ' + params.value
                                + (params.seriesData.length ? '：' + params.seriesData[0].data : '');
                        }
                    }
                },
                data: ["2018-1", "2018-2", "2018-3", "2018-4", "2018-5"]
            },
            {
                type: 'category',
                axisTick: {
                    alignWithLabel: true
                },
                axisLine: {
                    onZero: false,
                    lineStyle: {
                        color: colors[0]
                    }
                },
                axisPointer: {
                    label: {
                        formatter: function (params) {
                            return '每月用户数' + params.value
                                + (params.seriesData.length ? '：' + params.seriesData[0].data : '');
                        }
                    }
                },
                data: ["2018-1", "2018-2", "2018-3", "2018-4", "2018-5"]
            }
        ],
        yAxis: [
            {
                type: 'value'
            }
        ],
        series: [
            {
                name:'安卓（Andriod）每月用户数',
                type:'line',
                xAxisIndex: 1,
                smooth: true,
                data: [54125, 87454, 43441, 124571, 98456]
            },
            {
                name:'苹果（Ios）每月用户数',
                type:'line',
                smooth: true,
                data: [64548, 956410, 124141, 541541, 15414]
            }
        ]
    };
    if (option && typeof option === "object") {
        myChart.setOption(option, true);
    }
});
