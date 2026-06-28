function confirmarExclusao() {
    return confirm('Deseja realmente remover esta tarefa?');
}

// Valida se as senhas coincidem no cadastro
const formCadastro = document.getElementById('form-cadastro');
if (formCadastro) {
    formCadastro.addEventListener('submit', function (e) {
        const senha    = document.getElementById('senha').value;
        const confirma = document.getElementById('confirma').value;
        if (senha !== confirma) {
            e.preventDefault();
            alert('As senhas não coincidem!');
        }
    });
}