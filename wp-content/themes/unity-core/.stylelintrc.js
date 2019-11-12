module.exports = {
  'extends': 'stylelint-config-standard',
  'rules': {
    'no-empty-source': null,
    'color-hex-case': 'upper',
    'selector-list-comma-newline-after': null,
    'no-descending-specificity': null,
    'font-family-no-missing-generic-family-keyword': null,
    'function-linear-gradient-no-nonstandard-direction': null,
    'function-name-case': [
      'lower',
      {
        'ignoreFunctions': [
          '/^DXImageTransform.Microsoft.*$/',
        ],
      },
    ],
    'at-rule-empty-line-before': [
      'always',
      {
        'except': [
          'blockless-after-same-name-blockless',
          'first-nested',
          'after-same-name',
        ],
        'ignore': [
          'after-comment',
          'blockless-after-blockless',
        ],
      },
    ],
    'at-rule-no-unknown': [
      true,
      {
        'ignoreAtRules': [
          'extend',
          'at-root',
          'debug',
          'warn',
          'error',
          'if',
          'else',
          'for',
          'each',
          'while',
          'mixin',
          'include',
          'content',
          'return',
          'function',
          'tailwind',
          'apply',
          'responsive',
          'variants',
          'screen',
        ],
      },
    ],
  },
};
