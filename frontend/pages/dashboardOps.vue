<template>
  <div>
    <h1>Ops Dashboard</h1>
    <br />
    <el-row type="flex" class="row-bg" justify="center">
      <el-col :span="5" v-for="(topBox, i) in topBoxes" :key="i">
        <box-with-title-box :boxTheme="topBox.boxTheme">
          <template v-slot:header>
            <span>{{topBox.titleText}}</span>
          </template>
          <template v-slot:content>
            <div v-if="i==0">
              <el-date-picker
                v-model="value2"
                type="daterange"
                align="right"
                size="mini"
                unlink-panels
                start-placeholder="Start"
                format="MM/dd/yyyy"
                end-placeholder="End"
                :clearable="false"
                :picker-options="pickerOptions"
              ></el-date-picker>
            </div>
            <span v-if="i != 0">{{topBox.contentText}}</span>
          </template>
        </box-with-title-box>
      </el-col>
    </el-row>
    <el-row type="flex" class="row-bg" justify="center">
      <el-col :span="3">
        <box-with-header-and-footer boxTheme="blue"></box-with-header-and-footer>
      </el-col>
      <el-col :span="2">
        <bar-h barTheme="blue-black"></bar-h>
      </el-col>

      <el-col :span="3">
        <box-with-header-and-footer boxTheme="black"></box-with-header-and-footer>
      </el-col>
      <el-col :span="2">
        <bar-h barTheme="black-blue"></bar-h>
      </el-col>

      <el-col :span="3">
        <box-with-header-and-footer boxTheme="blue"></box-with-header-and-footer>
      </el-col>
      <el-col :span="2">
        <bar-h barTheme="blue-black"></bar-h>
      </el-col>

      <el-col :span="3">
        <box-with-header-and-footer boxTheme="black"></box-with-header-and-footer>
      </el-col>
      <el-col :span="2">
        <bar-h barTheme="black-green"></bar-h>
      </el-col>

      <el-col :span="3">
        <box-with-header-and-footer boxTheme="green"></box-with-header-and-footer>
      </el-col>
    </el-row>

    <el-row type="flex" :gutter="20" class="row-bg" justify="center">
      <el-col :span="7">
        <box-with-border>
          <template v-slot:header>
            <span>SALES</span>
          </template>
          <template v-slot:content>
            <el-table :data="tableData" style="width: 100%">
              <el-table-column prop="category" label width="64"></el-table-column>
              <el-table-column prop="goal" label="GOAL"></el-table-column>
              <el-table-column prop="actual" label="ACTUAL"></el-table-column>
              <el-table-column prop="percentageOfGoal" label="% of GOAL"></el-table-column>
            </el-table>
          </template>
        </box-with-border>
      </el-col>

      <el-col :span="9">
        <box-with-border :hasBorderForContent="false">
          <template v-slot:header>
            <span>LABOR 32.87%</span>
          </template>
          <template v-slot:content>
            <el-row
              type="flex"
              class="row-bg"
              justify="center"
              style="width: 100%; margin-top: 16px;"
            >
              <el-col :span="11">
                <box-with-title-box boxTheme="green-black medium-content">
                  <template v-slot:header>
                    <span>MANAGEMENT</span>
                  </template>
                  <template v-slot:content>
                    <span>9.93%</span>
                  </template>
                </box-with-title-box>
              </el-col>
            </el-row>
            <el-row type="flex" class="row-bg" justify="center" style="width: 100%">
              <el-col :span="11">
                <box-with-title-box boxTheme="green-black medium-content">
                  <template v-slot:header>
                    <span>MANAGEMENT</span>
                  </template>
                  <template v-slot:content>
                    <span>9.93%</span>
                  </template>
                </box-with-title-box>
              </el-col>
              <el-col :span="11">
                <box-with-title-box boxTheme="green-black medium-content">
                  <template v-slot:header>
                    <span>MANAGEMENT</span>
                  </template>
                  <template v-slot:content>
                    <span>9.93%</span>
                  </template>
                </box-with-title-box>
              </el-col>
            </el-row>
          </template>
        </box-with-border>
      </el-col>

      <el-col :span="7">
        <box-with-border>
          <template v-slot:header>
            <span>DIRECT OF. EXAMPLES</span>
          </template>
          <template v-slot:content>
            <el-table :data="tableData" style="width: 100%">
              <el-table-column prop="category" label width="64"></el-table-column>
              <el-table-column prop="goal" label="GOAL"></el-table-column>
              <el-table-column prop="actual" label="ACTUAL"></el-table-column>
              <el-table-column prop="percentageOfGoal" label="% of GOAL"></el-table-column>
            </el-table>
          </template>
        </box-with-border>
      </el-col>
    </el-row>

    <el-row type="flex" class="row-bg" justify="center">
      <el-col :span="5" v-for="(bottomBox, i) in bottomBoxes" :key="i">
        <box-with-title-box :boxTheme="bottomBox.boxTheme">
          <template v-slot:header>
            <span>{{bottomBox.titleText}}</span>
          </template>
          <template v-slot:content>
            <span>{{bottomBox.contentText}}</span>
          </template>
        </box-with-title-box>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import BoxWithTitleBox from "@/components/BoxWithTitleBox";
import BoxWithHeaderAndFooter from "@/components/BoxWithHeaderAndFooter";
import BarH from "@/components/BarH";
import BoxWithBorder from "@/components/BoxWithBorder";

const amt = (d, i) => d.find(r => r.p === i).amount;

export default {
  middleware: "authenticated",
  layout: "app",
  components: {
    BoxWithTitleBox,
    BoxWithHeaderAndFooter,
    BarH,
    BoxWithBorder
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
      // datePickerOptions: {
      //   disabledDate(date) {
      //     return date > new Date() || date < new Date("01/01/2018");
      //   }
      // },
      pickerOptions: {
        shortcuts: [
          {
            text: "Last week",
            onClick(picker) {
              let curr = new Date(); // get current date
              let first = curr.getDate() - curr.getDay() - 6; // First day is the day of the month - the day of the week
              let last = first + 6; // last day is the first day + 6

              let firstday = new Date(curr.setDate(first)).toUTCString();
              let lastday = new Date(curr.setDate(last)).toUTCString();

              picker.$emit("pick", [firstday, lastday]);
            }
          },
          {
            text: "Last month",
            onClick(picker) {
              // const end = new Date();
              // const start = new Date();
              // start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
              // picker.$emit("pick", [start, end]);
              let curr = new Date();
              let month = curr.getMonth();
              let year = curr.getFullYear();

              let firstDay = new Date(year, month -1 , 1);
              let lastDay = new Date(year, month , 0);

              picker.$emit("pick", [firstDay, lastDay]);

            }
          },
          {
            text: "Last 3 months",
            onClick(picker) {
              // const end = new Date();
              // const start = new Date();
              // start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
              // picker.$emit("pick", [start, end]);
              let curr = new Date();
              let month = curr.getMonth();
              let year = curr.getFullYear();

              let firstDay = new Date(year, month - 3 , 1);
              let lastDay = new Date(year, month , 0);

              picker.$emit("pick", [firstDay, lastDay]);
            }
          }
        ]
      },
      value2: "",
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
          contentText: "01/21/2019 - 01/21/2020",
          boxTheme: "green-blue"
        },
        {
          id: 2,
          titleText: "SALES",
          contentText: "$258,613",
          boxTheme: "green-blue"
        },
        {
          id: 3,
          titleText: "GOAL",
          contentText: "$313,787",
          boxTheme: "green-blue"
        },
        {
          id: 4,
          titleText: "LAST YEAR SALES",
          contentText: "$303,402",
          boxTheme: "green-blue"
        }
      ],
      bottomBoxes: [
        {
          id: 1,
          titleText: "GROSS MARGIN",
          contentText: "41.7%",
          boxTheme: "green-dark-green large-content"
        },
        {
          id: 2,
          titleText: "IFO",
          contentText: "27.6%",
          boxTheme: "green-dark-green large-content"
        },
        {
          id: 3,
          titleText: "NET INCOME",
          contentText: "14.4%",
          boxTheme: "green-dark-green large-content"
        },
        {
          id: 4,
          titleText: "DISCOUNTS",
          contentText: "3.19%",
          boxTheme: "green-dark-green large-content"
        }
      ],
      tableData: [
        {
          category: "Food",
          goal: "217,163",
          actual: "140,000",
          percentageOfGoal: "63"
        },
        {
          category: "Food",
          goal: "217,163",
          actual: "140,000",
          percentageOfGoal: "63"
        },
        {
          category: "Food",
          goal: "217,163",
          actual: "140,000",
          percentageOfGoal: "63"
        },
        {
          category: "Food",
          goal: "217,163",
          actual: "140,000",
          percentageOfGoal: "63"
        },
        {
          category: "Food",
          goal: "217,163",
          actual: "140,000",
          percentageOfGoal: "63"
        },
        {
          category: "Food",
          goal: "217,163",
          actual: "140,000",
          percentageOfGoal: "63"
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
.el-date-editor {
  width: 100%;
  color: white;
  &.el-input__inner {
    border: none !important;
    background: transparent !important;
    padding: 3px 8px !important;
  }
  /deep/ {
    .el-range__icon {
      color: white !important;
    }
    .el-range-separator {
      color: white !important;
    }
    .el-range-input {
      background: transparent !important;
      color: white !important;
      font-size: 14px !important;
      font-weight: 600 !important;
      -webkit-transform: scale(0.9, 1);
      -moz-transform: scale(0.9, 1);
      -ms-transform: scale(0.9, 1);
      -o-transform: scale(0.9, 1);
      transform: scale(0.9, 1);
      width: 100%;
      &::placeholder {
        color: white;
      }
    }
  }
}
</style>