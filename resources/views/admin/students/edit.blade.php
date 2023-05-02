@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.students.index') }}" class="btn btn-md btn-info" title="Listar registros cadastrados">
            <i class="fa fa-list mr-1"></i> Listar registro
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{route('admin.student.update',['id' => $student->id])}}" onsubmit="return mySubmit()" enctype="multipart/form-data" autocomplete="off">
            @csrf
            @method('PUT')
            <div class="card card-info" style="max-width: 1024px; margin: auto">
                <div class="card-header">
                    <h3 class="card-title">Formulário de edição de dados do aluno:</h3>
                </div>
                <div class="card-body">

                    <div class="widget-user-image">
                        @if (isset($student->image))
                            <img class="img-circle img-thumbnail" style="width: 100px; height: 100px;"
                                src="{{ asset('storage/' . $student->image) }}" alt="User Avatar"/>
                        @else
                            <img class="img-circle img-thumbnail" style="width: 100px; height: 100px;"
                                src="{{ asset('images/not-photo.jpg') }}" alt="User Avatar"/>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <small>Foto do aluno:</small>
                            <div class="form-group">
                                <input type="file" name="image" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group m-0">
                                <small>Instituição:</small>
                                <select name="institution" class="form-control">
                                    <option value="setbal" @if($student->institution == 'setbal') selected @endif>Setbal</option>
                                    <option value="ead" @if($student->institution == 'ead') selected @endif>EAD</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group m-0">
                                <small>Ano: *</small>
                                <select name="year" class="form-control">
                                    <option value="1" @if ($student->year == 1) selected @endif>1º ano</option>
                                    <option value="2" @if ($student->year == 2) selected @endif>2º ano</option>
                                    <option value="3" @if ($student->year == 3) selected @endif>3º ano</option>
                                    <option value="4" @if ($student->year == 4) selected @endif>4º ano</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group m-0">
                                <small>Matrícula:</small>
                                <input type="text" name="registration" value="{{ $student->registration ?? old('registration') }}" maxlength="10" class="form-control" placeholder="Nº"/>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-0">
                                <small>Nome completo: *</small>
                                <input type="text" name="name" value="{{ $student->name ?? old('name') }}" maxlength="100" class="form-control @error('name') is-invalid @enderror"/>
                                @error('name')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>E-mail: *</small>
                                <input type="email" name="email" value="{{ $student->email ?? old('email') }}" maxlength="100" class="form-control @error('email') is-invalid @enderror"/>
                                @error('email')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Telefone:</small>
                                <input type="text" name="phone" value="{{ $student->phone ?? old('phone') }}" onkeyup="handlePhone(event)" maxlength="15" class="form-control @error('phone') is-invalid @enderror"/>
                                @error('phone')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Senha:</small>
                                <input type="password" name="password" value="{{ old('password') }}" maxlength="30" class="form-control @error('password') is-invalid @enderror"/>
                                @error('password')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Confirme sua senha: *</small>
                                <input type="password" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    placeholder="******">
                                @error('password_confirmation')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>RG/CIC: *</small>
                                <input type="text" name="rg" value="{{ $student->rg ?? old('rg') }}" maxlength="30" class="form-control @error('rg') is-invalid @enderror"/>
                                @error('rg')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>CPF: *</small>
                                <input type="text" name="cpf" value="{{ $student->cpf ?? old('cpf') }}" onkeypress="mascara(this, '###.###.###-##')" maxlength="14" class="form-control @error('cpf') is-invalid @enderror"/>
                                @error('cpf')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-0">
                                <small>Filiação:</small>
                                <input type="text" name="filiation" value="{{ $student->filiation ?? old('filiation') }}" maxlength="100" class="form-control @error('filiation') is-invalid @enderror" />
                                @error('filiation')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Sexo:</small>
                                <select name="sexo" class="form-control">
                                    <option value=""></option>
                                    <option value="M" @if ($student->sexo == 'M') selected @endif>Masculino</option>
                                    <option value="F" @if ($student->sexo == 'F') selected @endif>Feminino</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Data de entrada:</small>
                                <input type="date" name="date_entry" value="{{ $student->date_entry ?? old('date_entry') }}" class="form-control @error('date_entry') is-invalid @enderror" />
                                @error('date_entry')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Data de saída:</small>
                                <input type="date" name="date_exit" value="{{ $student->date_exit ?? old('date_exit') }}" class="form-control @error('date_exit') is-invalid @enderror" />
                                @error('date_exit')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Ano de conclusão:</small>
                                <input type="text" name="conclusion" value="{{ $student->conclusion ?? old('conclusion') }}" maxlength="4" class="form-control @error('conclusion') is-invalid @enderror" placeholder="Ex: 2027"/>
                                @error('conclusion')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group m-0">
                                <small>Data de nascimento:</small>
                                <input type="date" name="birth_date" value="{{ $student->birth_date ?? old('birth_date') }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group m-0">
                                <small>Local de nascimento:</small>
                                <input type="text" name="birthplace" value="{{ $student->birthplace ?? old('birthplace') }}" maxlength="50" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-0">
                                <small>Igreja que frequenta:</small>
                                <input type="text" name="church" value="{{ $student->church ?? old('church') }}" maxlength="50" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group m-0">
                                <small>Naturalidade:</small>
                                <input type="text" name="naturalness" value="{{ $student->naturalness ?? old('naturalness') }}" maxlength="50" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group m-0">
                                <small>Nacionalidade:</small>
                                <input type="text" name="country" value="{{ $student->country ?? old('country') }}" maxlength="50" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3 m-0">
                            <div class="form-group m-0">
                                <small>Estado Civil:</small>
                                <select name="marital_status" class="form-control">
                                    <option value=""></option>
                                    <option value="1" @if ($student->marital_status == 1) selected @endif>Solteiro(a)</option>
                                    <option value="2" @if ($student->marital_status == 2) selected @endif>Casado(a)</option>
                                    <option value="3" @if ($student->marital_status == 3) selected @endif>Divorciado(a)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group m-0">
                                <small>Acesso: *</small>
                                <select name="access" class="form-control">
                                    <option value="active"  @if ($student->access == 'active') selected @endif>Liberado</option>
                                    <option value="inactive"  @if ($student->access == 'inactive') selected @endif>Bloqueado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>CEP: *</small>
                                <input type="text" 
                                       name="zip_code" 
                                       value="{{ $student->zip_code ?? old('zip_code') }}" 
                                       onkeypress="mascara(this, '#####-###')" 
                                        onblur="pesquisacep(this.value);"
                                       maxlength="9" 
                                       class="form-control @error('zip_code') is-invalid @enderror" 
                                       placeholder="00000-00"/>
                                @error('zip_code')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div class="form-group m-0">
                                <small>Endereço: *</small>
                                <input type="text" name="address" id="address" value="{{ $student->address ?? old('address') }}" maxlength="250" class="form-control @error('address') is-invalid @enderror"/>
                                @error('address')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group m-0">
                                <small>Nº: *</small>
                                <input type="text" name="number" id="number" value="{{ $student->number ?? old('number') }}"
                                    maxlength="5" class="form-control @error('number') is-invalid @enderror"/>
                                @error('number')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-0">
                                <small>Bairro: *</small>
                                <input type="text" name="district" id="district" value="{{ $student->district ?? old('district') }}" maxlength="50" class="form-control @error('district') is-invalid @enderror"/>
                                @error('district')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-0">
                                <small>Cidade: *</small>
                                <input type="text" name="city" id="city" value="{{ $student->city ?? old('city') }}" maxlength="50" class="form-control @error('city') is-invalid @enderror"/>
                                @error('city')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group m-0">
                                <small>Estado: *</small>
                                <input type="text" name="state" id="state" value="{{ $student->state ?? old('state') }}" maxlength="2" class="form-control @error('state') is-invalid @enderror"/>
                                @error('state')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.students.index') }}" type="submit" class="btn btn-default">Cancelar</a>
                    <button id="button" type="submit" class="btn btn-md btn-info float-right" style="display: block;">
                        <i class="fas fa-save mr-1"></i>
                        Salvar dados
                    </button>
                    <button type="button" id="spinner" class="btn btn-md btn-info float-right text-center"
                        style="display: none;">
                        <div id="spinner" class="spinner-border" role="status" style="width: 20px; height: 20px;">
                            <span class="sr-only">Loading...</span>
                        </div>
                        Enviando...
                    </button>
                </div>
            </div>
        </form>
        <br>
    </div>
@stop

@section('css')

@stop

@section('js')

    <!-- butons -->
    <script>
        document.getElementById("button").style.display = "block";
        document.getElementById("spinner").style.display = "none";

        function mySubmit() {
            document.getElementById("button").style.display = "none";
            document.getElementById("spinner").style.display = "block";
        }
    </script>
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
    <script>
        const handlePhone = (event) => {
            let input = event.target
            input.value = phoneMask(input.value)
        }

        const phoneMask = (value) => {
            if (!value) return ""
            value = value.replace(/\D/g, '')
            value = value.replace(/(\d{2})(\d)/, "($1) $2")
            value = value.replace(/(\d)(\d{4})$/, "$1-$2")
            return value
        }

        //criação de mascara
        function mascara(t, mask) {
            var i = t.value.length;
            var saida = mask.substring(1, 0);
            var texto = mask.substring(i)
            if (texto.substring(0, 1) != saida) {
                t.value += texto.substring(0, 1);
            }
        }

        // Busca pelo cep
        function limpa_formulario_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('address').value = ("");
            document.getElementById('number').value = ("");
            document.getElementById('district').value = ("");
            document.getElementById('city').value = ("");
            document.getElementById('state').value = ("");
        }

        function meu_callback(conteudo) {
            if (!("erro" in conteudo)) {
                //Atualiza os campos com os valores.
                document.getElementById('address').value = (conteudo.logradouro);
                document.getElementById('district').value = (conteudo.bairro);
                document.getElementById('city').value = (conteudo.localidade);
                document.getElementById('state').value = (conteudo.uf);
                document.getElementById('number').focus();

            } else {
                //CEP não Encontrado.
                limpa_formulário_cep();
                alert("CEP não encontrado.");
            }
        }

        function pesquisacep(valor) {
            //Nova variável "cep" somente com dígitos.
            var cep = valor.replace(/\D/g, '');
            //Verifica se campo cep possui valor informado.
            if (cep != "") {
                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;
                //Valida o formato do CEP.
                if (validacep.test(cep)) {
                    //Preenche os campos com "..." enquanto consulta webservice.
                    document.getElementById('address').value = "...";
                    document.getElementById('district').value = "...";
                    document.getElementById('city').value = "...";
                    document.getElementById('state').value = "...";
                    //Cria um elemento javascript.
                    var script = document.createElement('script');
                    //Sincroniza com o callback.
                    script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=meu_callback';
                    //Insere script no documento e carrega o conteúdo.
                    document.body.appendChild(script);

                } else {
                    //cep é inválido.
                    limpa_formulário_cep();
                    alert("Formato de CEP inválido.");
                }
            } //end if.
            else {
                //cep sem valor, limpa formulário.
                limpa_formulário_cep();
            }
        };
    </script>
@stop
