export const NumberUtils = {
  data() {
    return {};
  },
  methods: {
    round(num, decimales = 2, divider = false) {
      const signo = (num >= 0 ? 1 : -1);
      num *= signo;
      if (decimales === 0) { // con 0 decimales
        return signo * Math.round(num);
      }
      // round(x * 10 ^ decimales)
      num = num.toString().split('e');
      num = Math.round(+(`${num[0]}e${num[1] ? (+num[1] + decimales) : decimales}`));
      // x * 10 ^ (-decimales)
      num = num.toString().split('e');
      const number = signo * (`${num[0]}e${num[1] ? (+num[1] - decimales) : -decimales}`);
      if (divider) {
        return this.DividerFormat(number);
      }
      return number;
    },
    roundDecimal(num, decimales = 2) {
      const signo = (num >= 0 ? 1 : -1);
      num *= signo;
      if (decimales === 0) { // con 0 decimales
        return signo * Math.round(num);
      }
      // round(x * 10 ^ decimales)
      num = num.toString().split('e');
      num = Math.round(+(`${num[0]}e${num[1] ? (+num[1] + decimales) : decimales}`));
      // x * 10 ^ (-decimales)
      num = num.toString().split('e');
      return signo * (`${num[0]}e${num[1] ? (+num[1] - decimales) : -decimales}`);
    },
    intlRound(numero) {
      const opciones = {
        maximumFractionDigits: 2,
        minimumFractionDigits: 2,
      };
      if (numero) {
        return Number(numero).toLocaleString('en', opciones);
      }
      return Number(0.0).toLocaleString('en', opciones);
    },
    DividerFormat(numb) {
      const str = numb.toString().split('.');
      str[0] = str[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
      return str.join('.');
    },
    ExecuteForm(data) {
      let { formula } = data;
      data.parametros.map((item, index) => {
        formula = formula.replace(item, data.value[index]);
      });
      return eval(formula);
    },
  },
};
