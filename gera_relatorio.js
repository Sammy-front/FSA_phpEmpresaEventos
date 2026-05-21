const fs = require('fs');
const path = require('path');

// --- CONFIGURAÇÃO ---
// Especifique o caminho para a pasta que você deseja analisar.
const diretorioInicialRelativo = 'C:/xampp/htdocs/FSA/FSA_phpEmpresaEventos/Eventos';

// Especifique o nome do arquivo de texto que será gerado.
const arquivoDeSaida = 'relatorio_codigo.txt';

// Extensões de arquivos que o script deve ler e copiar o conteúdo.
const extensoesPermitidas = ['.js', '.jsx', '.php', '.sql']; // <-- ADICIONADO AQUI .php e .sql

// Lista de diretórios e arquivos a serem ignorados.
const listaDeIgnorados = [
  'node_modules',
  '.git',
  '.vscode',
  '.next', // <-- ADICIONADO AQUI PARA IGNORAR O NEXT.JS
  'dist',
  'build',
  'package.json',
  'package-lock.json',
  'yarn.lock',
  '.env',
];
// --- FIM DA CONFIGURAÇÃO ---

// Converte o caminho inicial para um caminho absoluto.
const diretorioAbsolutoParaAnalisar = path.resolve(diretorioInicialRelativo);

if (fs.existsSync(arquivoDeSaida)) {
  fs.unlinkSync(arquivoDeSaida);
}

/**
 * Função recursiva para ler todos os arquivos em um diretório e seus subdiretórios.
 * @param {string} diretorioAbsoluto - O caminho absoluto do diretório a ser lido.
 */
function lerDiretoriosRecursivamente(diretorioAbsoluto) {
  try {
    const itens = fs.readdirSync(diretorioAbsoluto);

    for (const item of itens) {
      if (listaDeIgnorados.includes(item)) {
        continue;
      }

      const caminhoAbsolutoItem = path.join(diretorioAbsoluto, item);

      try {
        const stats = fs.lstatSync(caminhoAbsolutoItem);

        if (stats.isSymbolicLink()) {
          console.log(`Ignorando link simbólico: ${caminhoAbsolutoItem}`);
          continue;
        }

        if (stats.isDirectory()) {
          lerDiretoriosRecursivamente(caminhoAbsolutoItem);
        } else if (stats.isFile()) {
          
          // Pega a extensão do arquivo em minúsculo (ex: '.php')
          const extensaoDoArquivo = path.extname(caminhoAbsolutoItem).toLowerCase();

          // Verifica se a extensão do arquivo está na nossa lista de permitidas
          if (extensoesPermitidas.includes(extensaoDoArquivo)) {
            console.log(`Lendo arquivo: ${caminhoAbsolutoItem}`);
            const conteudoDoArquivo = fs.readFileSync(caminhoAbsolutoItem, 'utf8');

            const dadosParaAdicionar = `
// =================================================================================
// Arquivo: ${caminhoAbsolutoItem}
// =================================================================================

${conteudoDoArquivo}

`;
            fs.appendFileSync(arquivoDeSaida, dadosParaAdicionar);
          }
        }
      } catch (statError) {
        console.warn(`AVISO: Não foi possível acessar '${caminhoAbsolutoItem}'. Erro: ${statError.code}. Pulando.`);
      }
    }
  } catch (readDirError) {
    console.error(`ERRO: Não foi possível ler o diretório '${diretorioAbsoluto}'. Erro: ${readDirError.code}. Pulando.`);
  }
}

console.log(`Iniciando a análise do diretório: ${diretorioAbsolutoParaAnalisar}`);
lerDiretoriosRecursivamente(diretorioAbsolutoParaAnalisar);
console.log(`\nProcesso finalizado! O arquivo "${arquivoDeSaida}" foi criado com sucesso.`);