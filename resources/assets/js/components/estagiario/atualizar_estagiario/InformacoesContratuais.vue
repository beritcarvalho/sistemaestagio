<template>
  <form @submit.prevent="inserirEstagiario" method="patch">
    <div class="row">
      <div class="col-md-3">
        <div class="row">
          <div class="col-md-10">
            <div class="form-group">
              <label for="selectVaga">Codigo Vaga</label>
              <select
                type="text"
                @click="carregaVaga"
                @change="selectVaga"
                @blur="validaVaga"
                :class="{'is-invalid': vagaValida}"
                class="form-control"
                id="selectVaga"
                v-model="post.cod_vaga"
              >
                <option></option>
                <option v-for="vaga of vagasLivres">{{ vaga.id }}</option>
              </select>
              <div v-if="vagaValida" class="invalid-feedback">Vaga não pode ser vazia</div>
            </div>
          </div>
          <div class="form-group d-flex flex-column align-items-baseline justify-content-end">
            <img
              class="cadastraVaga"
              @click="abreModalVaga"
              src="../../../../../../public/icones/icons8-adicionar-regra-48.png"
              alt="Adicionar Vaga"
            />
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <label for="inputSituacao">Situação</label>
          <select
            id="inputSituacao"
            @change="alteraVagaParaLivre"
            @blur="validaSituacao"
            :class="{'is-invalid':situacaoValida}"
            class="form-control"
            v-model="post.situacao"
          >
            <option value="1">CONTRATADO</option>
            <option value="5">DESLIGADO</option>
            <option value="2">EM CONTRATAÇÃO</option>
            <option value="3">EM DESLIGAMENTO</option>
            <!-- <option value="4">EM RENOVAÇÃO</option>
            <option value="6">TCE CANCELADO</option> -->
          </select>
          <div class="invalid-feedback">Situação não pode ser vazia</div>
        </div>
      </div>

      <!--STATUS DA VAGA SERÁ GERENCIADO VIA BD-->
      <div v-if="false" class="col-md-3">
        <div class="form-group">
          <label for="selectStatus">Status Vaga</label>
          <select type="text" id="selectStatus" class="form-control" v-model="statusVaga.status">
            <option default>{{ statusVaga.status }}</option>
            <option v-if="statusVaga.status != 'OCUPADA'">OCUPADA</option>
            <option v-if="statusVaga.status != 'EM SELEÇÃO'">EM SELEÇÃO</option>
            <option v-if="statusVaga.status != 'LIVRE'">LIVRE</option>
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="inputContrato">Contrato CIEE</label>
          <input
            type="text"
            maxlength="20"
            class="form-control"
            id="inputContrato"
            v-model="post.contrato"
          />
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="inputTceSuperEstagios">TCE Super Estágios</label>
          <input
            type="text"
            maxlength="11"
            class="form-control"
            id="inputTceSuperEstagios"
            v-model="post.tc_superestagios"
          />
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="selectDepartamento">Dep. Hierárquico</label>
          <select
            class="form-control"
            @blur="validaDepartamento"
            :class="{'is-invalid':departamentoValido}"
            id="selectDepartamento"
            v-model="post.dep_hierarquico"
          >
            <option>{{ post.dep_hierarquico }}</option>
            <option
              v-for="departamento of departamentos"
              v-if="departamento.tipo === 'PAI'"
            >{{ departamento.sigla }}</option>
          </select>
          <div class="invalid-feedback">Departamento não pode ficar em branco</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="setor-estag">
          <label for="selectSetor">Setor Estagiado</label>
          <select
            class="form-control"
            @blur="validaSetor"
            :class="{'is-invalid':setorValido}"
            id="selectSetor"
            v-model="post.setor_estagiado"
          >
            <option></option>
            <option
              v-for="departamento of departamentos"
              v-if="departamento.tipo === 'FILHO'"
            >{{ departamento.sigla }}</option>
          </select>
          <div class="invalid-feedback">Setor não pode ser vazio</div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-10">
            <div class="form-group">
              <label for="inputSupervisor">Supervisor</label>
              <select
                class="form-control"
                @click="carregaSupervisor"
                @blur="validaSupervisor"
                :class="{'is-invalid':supervisorValido}"
                id="inputSupervisor"
                v-model="post.supervisor"
                disabled="disabled"
              >
                <option>{{ post.supervisor }}</option>
              </select>
              <div class="invalid-feedback">Supervisor não pode ser vazio</div>
            </div>
          </div>
          <div class="form-group d-flex flex-column align-items-baseline justify-content-end">
            <img
              class="cadastraSupervisor"
              @click="abreModalSupervisor"
              src="../../../../../../public/icones/icons8-adicionar-usuario-masculino-30.png"
              alt="Adicionar Supervisor"
            />
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="inputInicio">Data Início</label>
          <input type="date" class="form-control" id="inputInicio" v-model="post.dt_inicio" />
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="inputTermino">Data Término</label>
          <input type="date" class="form-control" id="inputTermino" v-model="post.dt_termino" />
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="inputTerminoInicial">Término Inicial</label>
          <input
            type="date"
            class="form-control"
            id="inputTerminoInicial"
            v-model="post.dt_termino_inicial_lauda"
          />
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="inputPrimeiroInicioAditivo">Início Termo Aditivo 1</label>
          <input
            type="date"
            class="form-control"
            id="inputPrimeiroInicioAditivo"
            v-model="post.dt_inicio_1_aditivo"
          />
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="inputPrimeiroTerminoAditivo">Fim Termo Aditivo 1</label>
          <input
            type="date"
            class="form-control"
            id="inputPrimeiroTerminoAditivo"
            v-model="post.dt_termino_1_aditivo"
          />
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="inputSegundoInicioAditivo">Início Termo Aditivo 2</label>
          <input
            type="date"
            class="form-control"
            id="inputSegundoInicioAditivo"
            v-model="post.dt_inicio_2_aditivo"
          />
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="inputSegundoTerminoAditivo">Fim Termo Aditivo 2</label>
          <input
            type="date"
            class="form-control"
            id="inputSegundoTerminoAditivo"
            v-model="post.dt_termino_2_aditivo"
          />
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="inputTerceiroInicioAditivo">Início Termo Aditivo 3</label>
          <input
            type="date"
            class="form-control"
            id="inputTerceiroInicioAditivo"
            v-model="post.dt_inicio_3_aditivo"
          />
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="inputTerceiroTerminoAditivo">Início Termo Aditivo 3</label>
          <input
            type="date"
            class="form-control"
            id="inputTerceiroTerminoAditivo"
            v-model="post.dt_termino_3_aditivo"
          />
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="inputDataDesligamento">Desligado em</label>
          <input
            type="date"
            class="form-control"
            id="inputDataDesligamento"
            v-model="post.desligado"
          />
        </div>
      </div>
      <!-- DESATIVADO INDEFINIDAMENTE -->
      <div v-if="false" class="col-md-3">
        <div class="form-group">
          <label for="inputSemestreDesligamento">Semestre de desligamento</label>
          <input
            type="number"
            class="form-control"
            id="inputSemestreDesligamento"
            v-model="post.semestre_desligamento"
          />
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="inputHorarioEntrada">Horário de Entrada</label>
          <input
            type="time"
            @blur="validaHorarioEntrada"
            :class="{'is-invalid':horarioEntradaValido}"
            class="form-control"
            id="inputHorarioEntrada"
            v-model="post.horario_entrada"
          />
          <div class="invalid-feedback">Horário de entrada não pode ser vazio</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="inputHorarioSaida">Horário de Saída</label>
          <input
            type="time"
            @blur="validaHorarioSaida"
            :class="{'is-invalid':horarioSaidaValido}"
            class="form-control"
            id="inputHorarioSaida"
            v-model="post.horario_saida"
          />
          <div class="invalid-feedback">Horário de saída não pode ser vazio</div>
        </div>
      </div>
      <div class="col-md-3 d-flex flex-column justify-content-center">
        <div class="form-group">
          <span>Horário variável</span>
          <br />
          <input type="checkbox" v-model="post.horario_variavel" />
          <div class="invalid-feedback">Data de término não pode ser vazio</div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="inputObs">Observações</label>
          <textarea
            cols="30"
            rows="4"
            class="form-control"
            id="inputObs"
            v-model="post.observacao2"
          ></textarea>
        </div>
      </div>
    </div>
    <botoes-component :titulo="nomeBotao = 'Salvar'"></botoes-component>
  </form>
</template>
<script>
export default {
  data() {
    return {
      // vagas passíveis de utilização (incluindo a do próprio estagiário)
      vagasLivres: [],
      todasVagas: [],
      msg: {
        error: false,
        success: false
      }
    };
  },
  beforeMount() {
    const uriVagas = "/api/vagas";

    this.axios
      .get(uriVagas)
      .then(response => {
        this.msg.error = false;
        this.todasVagas = response.data;

        for (var i in this.todasVagas) {
          if (this.todasVagas[i].status.length === 5) {
            this.vagasLivres.push(this.todasVagas[i]);
          }
        }
        console.log(this.post.cod_vaga);
      })
      .catch(error => {
        this.msg.error = true;
        this.msg.erro = "Erro ao retornar vagas do banco de dados";
      });
  },
  watch: {
    post: function() {
      for (var i in this.todasVagas) {
        if (this.todasVagas[i].id === this.post.cod_vaga) {
          this.vagasLivres.push(this.todasVagas[i]);
          break;
        }
      }
    }
  },
  props: [
    "post",
    "departamentos",
    "supervisores",
    "inserirEstagiario",
    "alteracaoSupervisor",
    "horarioVariavel",
    "dataModificacao",
    "horaModificacao",
    "vagas",
    "validaVaga",
    "vagaValida",
    "selectVaga",
    "statusVaga",
    "validaDepartamento",
    "departamentoValido",
    "validaSetor",
    "setorValido",
    "validaSupervisor",
    "supervisorValido",
    "validaHorarioEntrada",
    "horarioEntradaValido",
    "validaHorarioSaida",
    "horarioSaidaValido",
    "validaSituacao",
    "situacaoValida",
    "alteraVagaParaLivre",
    "supervisoresOrdenados",
    "vagasOrdenadas",
    "carregaVaga",
    "abreModalVaga",
    "abreModalSupervisor",
    "carregaSupervisor"
  ]
};
</script>
<style>
</style>
