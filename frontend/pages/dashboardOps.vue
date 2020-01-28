<template>
  <div>
    <h1>Ops Dashboard</h1>
    <br />
    <el-row type="flex" class="row-bg" justify="center">
      <el-col :span="4" v-for="(topBox, i) in topBoxes" :key="i">
        <box-with-title-box :titleText="topBox.titleText" :contentText="topBox.contentText"></box-with-title-box>
      </el-col>
    </el-row>
    <el-row type="flex" class="row-bg" justify="center">
      <el-col :span="3">
        <box-with-header-and-footer
          boxTheme="blue" 
        ></box-with-header-and-footer>
      </el-col>
      <el-col :span="2">
        <bar-h
          barTheme = "blue-black"
        ></bar-h>
      </el-col>

      <el-col :span="3">
        <box-with-header-and-footer
          boxTheme="black" 
        ></box-with-header-and-footer>
      </el-col>
      <el-col :span="2">
        <bar-h
          barTheme = "black-blue"
        ></bar-h>
      </el-col>

      <el-col :span="3">
        <box-with-header-and-footer
          boxTheme="blue" 
        ></box-with-header-and-footer>
      </el-col>
      <el-col :span="2">
        <bar-h
          barTheme = "blue-black"
        ></bar-h>
      </el-col>

      <el-col :span="3">
        <box-with-header-and-footer
          boxTheme="black" 
        ></box-with-header-and-footer>
      </el-col>
      <el-col :span="2">
        <bar-h
          barTheme = "black-green"
        ></bar-h>
      </el-col>

      <el-col :span="3">
        <box-with-header-and-footer
          boxTheme="green" 
        ></box-with-header-and-footer>
      </el-col>
      
    </el-row>
    <div>
      <el-date-picker
        v-model="month"
        type="month"
        placeholder="Pick a month"
        :picker-options="datePickerOptions"
        @change="getPeriodNum"
      ></el-date-picker>
    </div>
  </div>
</template>

<script>
import BoxWithTitleBox from "@/components/BoxWithTitleBox";
import BoxWithHeaderAndFooter from "@/components/BoxWithHeaderAndFooter";
import BarH from "@/components/BarH";
const amt = (d, i) => d.find(r => r.p === i).amount;

export default {
  middleware: "authenticated",
  layout: "app",
  components: {
    BoxWithTitleBox,
    BoxWithHeaderAndFooter,
    BarH
  },
  async mounted() {
    let init = await this.$store.dispatch("graphs/periodReverse", new Date());
    this.currentPeriod = init;
    this.periodNum = init;

    this.period = await this.$store.dispatch("graphs/period", this.periodNum);
    this.month = new Date("01 " + this.period.month + " " + this.period.year);
  },
  data() {
    return {
      currentPeriod: 1,
      periodNum: 1,
      period: null,
      month: null,
      datePickerOptions: {
        disabledDate(date) {
          return date > new Date() || date < new Date("01/01/2018");
        }
      },
      months: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec"
      ],
      topBoxes: [
        {
          id: 1,
          titleText: "DATE RANGE",
          contentText: "01/21/2019 - 01/21/2020"
        },
        {
          id: 2,
          titleText: "SALES",
          contentText: "$258,613"
        },
        {
          id: 3,
          titleText: "GOAL",
          contentText: "$313,787"
        },
        {
          id: 4,
          titleText: "LAST YEAR SALES",
          contentText: "$303,402"
        }
      ]
    };
  },
  computed: {},
  methods: {
    async getPeriodNum() {
      this.periodNum = await this.$store.dispatch(
        "graphs/periodReverse",
        this.month
      );
    }
  }
};
</script>

<style scoped lang="scss">
.el-row {
  margin-bottom: 20px;
  &:last-child {
    margin-bottom: 0;
  }
}
.el-col {
  border-radius: 4px;
}

.row-bg {
  padding: 10px 0;
}
</style>