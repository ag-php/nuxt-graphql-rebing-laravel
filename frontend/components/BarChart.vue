<template>
  <div :class="className" :style="{height:height,width:width}" />
</template>

<script>
import echarts from 'echarts'
require('echarts/theme/macarons') // echarts theme
import resize from './mixins/resize'

const animationDuration = 6000

export default {
  mixins: [resize],
  props: {
    className: {
      type: String,
      default: 'chart'
    },
    width: {
      type: String,
      default: '80%'
    },
    height: {
      type: String,
      default: '500px'
    },
    series: {
      type: Array
    },
    source: {
      type: Array
    },
    title: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      chart: null
    }
  },
  mounted() {
    this.$nextTick(() => {
      this.initChart()
    })
  },
  beforeDestroy() {
    if (!this.chart) {
      return
    }
    this.chart.dispose()
    this.chart = null
  },
  methods: {
    initChart() {
      this.chart = echarts.init(this.$el, 'macarons')

      this.chart.setOption({
        title: this.title,
        legend: {
          bottom: '-5px',
          width: "200px"
        },
        tooltip: {},
        dataset: {
          source: this.source
        },
        xAxis: {type: 'category'},
        yAxis: {},
        series: this.series
      })
    }
  }
}
</script>
