<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

use function Laravel\Prompts\text;

class ScrapingController extends Controller
{
    public function _HandlePencarian(Request $request)
    {
        try {
            $symbol = $request->input('cari-nama');
            $url = 'https://finance.yahoo.com/quote/' . $symbol . '?p=' . $symbol . '&.tsrc=fin-srch';
            $htmlContent = file_get_contents($url);

            $crawler = new Crawler($htmlContent);

            if ($crawler->filter('#Lead-5-QuoteHeader-Proxy')->count()) {
                $information = $crawler->filter('#Lead-5-QuoteHeader-Proxy')->each(function (Crawler $row) {
                    $namaPT = $row->filter('h1')->text();
                    $detailInfo = $row->filter('span')->text();
                    $detailPrice = $row->filter('div.D\(ib\).Va\(m\).Maw\(65\%\).Ov\(h\) fin-streamer')->text();
                    return [
                        'title' => $namaPT,
                        'detailInfo' => $detailInfo,
                        'detailPrice' => $detailPrice,
                    ];
                });
            }

            if ($crawler->filter('#quote-nav')->count()) {
                $createButton = $crawler->filter('#quote-nav')->each(function ($item) {
                    $sumray = $item->filter('ul [data-test="SUMMARY"]')->count() ? $item->filter('ul [data-test="SUMMARY"]')->text() : '-';
                    $statistik = $item->filter('ul [data-test="STATISTICS"]')->count() ? $item->filter('ul [data-test="STATISTICS"]')->text() : '-';
                    $analisis = $item->filter('ul [data-test="ANALYSIS"]')->count() ? $item->filter('ul [data-test="ANALYSIS"]')->text() : '-';
                    $finansial = $item->filter('ul [data-test="FINANCIALS"]')->count() ? $item->filter('ul [data-test="FINANCIALS"]')->text() : '-';
                    $profile = $item->filter('ul [data-test="COMPANY_PROFILE"]')->count() ? $item->filter('ul [data-test="COMPANY_PROFILE"]')->text() : '-';
                    return [
                        'sumray' => $sumray,
                        'statistik' => $statistik,
                        'analisis' => $analisis,
                        'finansial' => $finansial,
                        'profile' => $profile,
                    ];
                });
            }

            if ($crawler->filter('div[data-test=left-summary-table] table tbody tr')->count()) {
                $stockDataKiri = $crawler->filter('div[data-test=left-summary-table] table tbody tr')->each(function ($row) {
                    $label = $row->filter('td:first-child span')->text();
                    $value = $row->filter('td:last-child')->text();
                    return [$label => $value];
                });
            }

            if ($crawler->filter('div[data-test=right-summary-table] table tbody tr')->count()) {
                $stockDataKanan = $crawler->filter('div[data-test=right-summary-table] table tbody tr')->each(function ($row) {
                    $label = $row->filter('td:first-child span')->text();
                    $value = $row->filter('td:last-child')->text();
                    return [$label => $value];
                });
            }

            return view('Components.Details', [
                'symbol' => $symbol,
                'information' => $information,
                'stock_data_kiri' => $stockDataKiri,
                'stock_data_kanan' => $stockDataKanan,
                'createButton' => $createButton,
            ]);
        } catch (\Exception $e) {
            return view('Components.PageError', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function _HandleStatistik($symbol)
    {
        try {
            $url = 'https://finance.yahoo.com/quote/' . $symbol . '/key-statistics?p=' . $symbol . '';
            $htmlContent = file_get_contents($url);

            $crawler = new Crawler($htmlContent);


            if ($crawler->filter('#Lead-5-QuoteHeader-Proxy')->count()) {
                $information = $crawler->filter('#Lead-5-QuoteHeader-Proxy')->each(function (Crawler $row) {
                    $namaPT = $row->filter('h1')->text();
                    $detailInfo = $row->filter('span')->text();
                    $detailPrice = $row->filter('div.D\(ib\).Va\(m\).Maw\(65\%\).Ov\(h\) fin-streamer')->text();
                    return [
                        'title' => $namaPT,
                        'detailInfo' => $detailInfo,
                        'detailPrice' => $detailPrice,
                    ];
                });
            }

            if ($crawler->filter('#quote-nav')->count()) {
                $createButton = $crawler->filter('#quote-nav')->each(function ($item) {
                    $sumray = $item->filter('ul [data-test="SUMMARY"]')->count() ? $item->filter('ul [data-test="SUMMARY"]')->text() : '-';
                    $statistik = $item->filter('ul [data-test="STATISTICS"]')->count() ? $item->filter('ul [data-test="STATISTICS"]')->text() : '-';
                    $analisis = $item->filter('ul [data-test="ANALYSIS"]')->count() ? $item->filter('ul [data-test="ANALYSIS"]')->text() : '-';
                    $finansial = $item->filter('ul [data-test="FINANCIALS"]')->count() ? $item->filter('ul [data-test="FINANCIALS"]')->text() : '-';
                    $profile = $item->filter('ul [data-test="COMPANY_PROFILE"]')->count() ? $item->filter('ul [data-test="COMPANY_PROFILE"]')->text() : '-';
                    return [
                        'sumray' => $sumray,
                        'statistik' => $statistik,
                        'analisis' => $analisis,
                        'finansial' => $finansial,
                        'profile' => $profile,
                    ];
                });
            }

            if ($crawler->filter('#mrt-node-Col1-0-KeyStatistics table tbody tr')->count()) {

                $dataFI = $crawler->filter('#mrt-node-Col1-0-KeyStatistics table tbody tr ')->each(function ($item) {
                    $label = $item->filter('td:first-child span')->text();
                    $value = $item->filter('td:last-child')->text();
                    return [
                        $label => $value,
                    ];
                });
            }

            return view('Components.Statistik', [
                'symbol' => $symbol,
                'information' => $information,
                'createButton' => $createButton,
                'dataFI' => $dataFI,
            ]);
        } catch (\Exception $e) {
            return view('Components.PageError', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function _HandleAnalisis($symbol)
    {
        try {
            $url = 'https://finance.yahoo.com/quote/' . $symbol . '/analysis?p=' . $symbol . '';
            $htmlContent = file_get_contents($url);

            $crawler = new Crawler($htmlContent);


            if ($crawler->filter('#Lead-5-QuoteHeader-Proxy')->count()) {
                $information = $crawler->filter('#Lead-5-QuoteHeader-Proxy')->each(function (Crawler $row) {
                    $namaPT = $row->filter('h1')->text();
                    $detailInfo = $row->filter('span')->text();
                    $detailPrice = $row->filter('div.D\(ib\).Va\(m\).Maw\(65\%\).Ov\(h\) fin-streamer')->text();
                    return [
                        'title' => $namaPT,
                        'detailInfo' => $detailInfo,
                        'detailPrice' => $detailPrice,
                    ];
                });
            }

            if ($crawler->filter('#quote-nav')->count()) {
                $createButton = $crawler->filter('#quote-nav')->each(function ($item) {
                    $sumray = $item->filter('ul [data-test="SUMMARY"]')->count() ? $item->filter('ul [data-test="SUMMARY"]')->text() : '-';
                    $statistik = $item->filter('ul [data-test="STATISTICS"]')->count() ? $item->filter('ul [data-test="STATISTICS"]')->text() : '-';
                    $analisis = $item->filter('ul [data-test="ANALYSIS"]')->count() ? $item->filter('ul [data-test="ANALYSIS"]')->text() : '-';
                    $finansial = $item->filter('ul [data-test="FINANCIALS"]')->count() ? $item->filter('ul [data-test="FINANCIALS"]')->text() : '-';
                    $profile = $item->filter('ul [data-test="COMPANY_PROFILE"]')->count() ? $item->filter('ul [data-test="COMPANY_PROFILE"]')->text() : '-';
                    return [
                        'sumray' => $sumray,
                        'statistik' => $statistik,
                        'analisis' => $analisis,
                        'finansial' => $finansial,
                        'profile' => $profile,
                    ];
                });
            }

            if ($crawler->filter('section[data-test="qsp-analyst"] table')->count()) {
                $countTable = $crawler->filter('section[data-test="qsp-analyst"] table')->each(function ($table) {
                    return [
                        'labels' => $table->filter('thead tr th')->each(function ($label) {
                            return $label->text();
                        }),
                        'values' => $table->filter('tbody tr')->each(function ($row) {
                            return $row->filter('td')->each(function ($cell) {
                                return $cell->text();
                            });
                        }),
                    ];
                });
            }

            return view('Components.Analisis', [
                'symbol' => $symbol,
                'information' => $information,
                'createButton' => $createButton,
                'countTable' => $countTable,
            ]);
        } catch (\Exception $e) {
            return view('Components.PageError', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function _HandleFinansial($symbol)
    {
        try {
            $url = 'https://finance.yahoo.com/quote/' . $symbol . '/financials?p=' . $symbol . '';
            $htmlContent = file_get_contents($url);

            $crawler = new Crawler($htmlContent);


            if ($crawler->filter('#Lead-5-QuoteHeader-Proxy')->count()) {
                $information = $crawler->filter('#Lead-5-QuoteHeader-Proxy')->each(function (Crawler $row) {
                    $namaPT = $row->filter('h1')->text();
                    $detailInfo = $row->filter('span')->text();
                    $detailPrice = $row->filter('div.D\(ib\).Va\(m\).Maw\(65\%\).Ov\(h\) fin-streamer')->text();
                    return [
                        'title' => $namaPT,
                        'detailInfo' => $detailInfo,
                        'detailPrice' => $detailPrice,
                    ];
                });
            }

            if ($crawler->filter('#quote-nav')->count()) {
                $createButton = $crawler->filter('#quote-nav')->each(function ($item) {
                    $sumray = $item->filter('ul [data-test="SUMMARY"]')->count() ? $item->filter('ul [data-test="SUMMARY"]')->text() : '-';
                    $statistik = $item->filter('ul [data-test="STATISTICS"]')->count() ? $item->filter('ul [data-test="STATISTICS"]')->text() : '-';
                    $analisis = $item->filter('ul [data-test="ANALYSIS"]')->count() ? $item->filter('ul [data-test="ANALYSIS"]')->text() : '-';
                    $finansial = $item->filter('ul [data-test="FINANCIALS"]')->count() ? $item->filter('ul [data-test="FINANCIALS"]')->text() : '-';
                    $profile = $item->filter('ul [data-test="COMPANY_PROFILE"]')->count() ? $item->filter('ul [data-test="COMPANY_PROFILE"]')->text() : '-';
                    return [
                        'sumray' => $sumray,
                        'statistik' => $statistik,
                        'analisis' => $analisis,
                        'finansial' => $finansial,
                        'profile' => $profile,
                    ];
                });
            }

            if ($crawler->filter('#Col1-1-Financials-Proxy')->count()) {
                $data_income_steatmen = $crawler->filter('#Col1-1-Financials-Proxy')->each(function ($item) {
                    return
                        [
                            'title' => $item->filter('.Mb\(10px\) h3')->each(function ($title) {
                                return $title->text();
                            }),

                            'labels' => $item->filter('div.D\(tbr\).C\(\$\primaryColor\) 
                            ')->each(function ($labels) {
                                return $labels->filter('span')->each(function ($label) {
                                    return $label->text();
                                });
                            }),

                            'values' => $item->filter('div.D\(tbrg\) div[data-test="fin-row"]')->each(function ($row) {
                                return $row->filter('div.D\(tbc\)')->each(function ($value) {
                                    return $value->text();
                                });
                            })

                        ];
                });
            }

            return view('Components.Finansial', [
                'symbol' => $symbol,
                'information' => $information,
                'createButton' => $createButton,
                'data_income_steatmen' => $data_income_steatmen,
            ]);
        } catch (\Exception $e) {
            return view('Components.PageError', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function _HandleNeraca($symbol)
    {
        try {
            $url = 'https://finance.yahoo.com/quote/' . $symbol . '/balance-sheet?p=' . $symbol . '';
            $htmlContent = file_get_contents($url);

            $crawler = new Crawler($htmlContent);


            if ($crawler->filter('#Lead-5-QuoteHeader-Proxy')->count()) {
                $information = $crawler->filter('#Lead-5-QuoteHeader-Proxy')->each(function (Crawler $row) {
                    $namaPT = $row->filter('h1')->text();
                    $detailInfo = $row->filter('span')->text();
                    $detailPrice = $row->filter('div.D\(ib\).Va\(m\).Maw\(65\%\).Ov\(h\) fin-streamer')->text();
                    return [
                        'title' => $namaPT,
                        'detailInfo' => $detailInfo,
                        'detailPrice' => $detailPrice,
                    ];
                });
            }


            if ($crawler->filter('#quote-nav')->count()) {
                $createButton = $crawler->filter('#quote-nav')->each(function ($item) {
                    $sumray = $item->filter('ul [data-test="SUMMARY"]')->count() ? $item->filter('ul [data-test="SUMMARY"]')->text() : '-';
                    $statistik = $item->filter('ul [data-test="STATISTICS"]')->count() ? $item->filter('ul [data-test="STATISTICS"]')->text() : '-';
                    $analisis = $item->filter('ul [data-test="ANALYSIS"]')->count() ? $item->filter('ul [data-test="ANALYSIS"]')->text() : '-';
                    $finansial = $item->filter('ul [data-test="FINANCIALS"]')->count() ? $item->filter('ul [data-test="FINANCIALS"]')->text() : '-';
                    $profile = $item->filter('ul [data-test="COMPANY_PROFILE"]')->count() ? $item->filter('ul [data-test="COMPANY_PROFILE"]')->text() : '-';
                    return [
                        'sumray' => $sumray,
                        'statistik' => $statistik,
                        'analisis' => $analisis,
                        'finansial' => $finansial,
                        'profile' => $profile,
                    ];
                });
            }

            if ($crawler->filter('#Col1-1-Financials-Proxy')->count()) {
                $data_income_steatmen = $crawler->filter('#Col1-1-Financials-Proxy')->each(function ($item) {
                    return
                        [
                            'title' => $item->filter('.Mb\(10px\) h3')->each(function ($title) {
                                return $title->text();
                            }),

                            'labels' => $item->filter('div.D\(tbr\).C\(\$\primaryColor\) 
                            ')->each(function ($labels) {
                                return $labels->filter('span')->each(function ($label) {
                                    return $label->text();
                                });
                            }),

                            'values' => $item->filter('div.D\(tbrg\) div[data-test="fin-row"]')->each(function ($row) {
                                return $row->filter('div.D\(tbc\)')->each(function ($value) {
                                    return $value->text();
                                });
                            })

                        ];
                });
            }

            return view('Components.Neraca', [
                'symbol' => $symbol,
                'information' => $information,
                'createButton' => $createButton,
                'data_income_steatmen' => $data_income_steatmen,
            ]);
        } catch (\Exception $e) {
            return view('Components.PageError', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function _HandleArus_Khas($symbol)
    {
        try {
            $url = 'https://finance.yahoo.com/quote/' . $symbol . '/cash-flow?p=' . $symbol . '';
            $htmlContent = file_get_contents($url);

            $crawler = new Crawler($htmlContent);


            if ($crawler->filter('#Lead-5-QuoteHeader-Proxy')->count()) {
                $information = $crawler->filter('#Lead-5-QuoteHeader-Proxy')->each(function (Crawler $row) {
                    $namaPT = $row->filter('h1')->text();
                    $detailInfo = $row->filter('span')->text();
                    $detailPrice = $row->filter('div.D\(ib\).Va\(m\).Maw\(65\%\).Ov\(h\) fin-streamer')->text();
                    return [
                        'title' => $namaPT,
                        'detailInfo' => $detailInfo,
                        'detailPrice' => $detailPrice,
                    ];
                });
            }


            if ($crawler->filter('#quote-nav')->count()) {
                $createButton = $crawler->filter('#quote-nav')->each(function ($item) {
                    $sumray = $item->filter('ul [data-test="SUMMARY"]')->count() ? $item->filter('ul [data-test="SUMMARY"]')->text() : '-';
                    $statistik = $item->filter('ul [data-test="STATISTICS"]')->count() ? $item->filter('ul [data-test="STATISTICS"]')->text() : '-';
                    $analisis = $item->filter('ul [data-test="ANALYSIS"]')->count() ? $item->filter('ul [data-test="ANALYSIS"]')->text() : '-';
                    $finansial = $item->filter('ul [data-test="FINANCIALS"]')->count() ? $item->filter('ul [data-test="FINANCIALS"]')->text() : '-';
                    $profile = $item->filter('ul [data-test="COMPANY_PROFILE"]')->count() ? $item->filter('ul [data-test="COMPANY_PROFILE"]')->text() : '-';
                    return [
                        'sumray' => $sumray,
                        'statistik' => $statistik,
                        'analisis' => $analisis,
                        'finansial' => $finansial,
                        'profile' => $profile,
                    ];
                });
            }


            if ($crawler->filter('#Col1-1-Financials-Proxy')->count()) {
                $data_income_steatmen = $crawler->filter('#Col1-1-Financials-Proxy')->each(function ($item) {
                    return
                        [
                            'title' => $item->filter('.Mb\(10px\) h3')->each(function ($title) {
                                return $title->text();
                            }),

                            'labels' => $item->filter('div.D\(tbr\).C\(\$\primaryColor\) 
                            ')->each(function ($labels) {
                                return $labels->filter('span')->each(function ($label) {
                                    return $label->text();
                                });
                            }),

                            'values' => $item->filter('div.D\(tbrg\) div[data-test="fin-row"]')->each(function ($row) {
                                return $row->filter('div.D\(tbc\)')->each(function ($value) {
                                    return $value->text();
                                });
                            })

                        ];
                });
            }

            return view('Components.Arus-Khas', [
                'symbol' => $symbol,
                'information' => $information,
                'createButton' => $createButton,
                'data_income_steatmen' => $data_income_steatmen,
            ]);
        } catch (\Exception $e) {
            return view('Components.PageError', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function _HandleProfile($symbol)
    {
        try {
            $url = 'https://finance.yahoo.com/quote/' . $symbol . '/profile?p=' . $symbol . '';
            $htmlContent = file_get_contents($url);

            $crawler = new Crawler($htmlContent);


            if ($crawler->filter('#Lead-5-QuoteHeader-Proxy')->count()) {
                $information = $crawler->filter('#Lead-5-QuoteHeader-Proxy')->each(function (Crawler $row) {
                    $namaPT = $row->filter('h1')->text();
                    $detailInfo = $row->filter('span')->text();
                    $detailPrice = $row->filter('div.D\(ib\).Va\(m\).Maw\(65\%\).Ov\(h\) fin-streamer')->text();
                    return [
                        'title' => $namaPT,
                        'detailInfo' => $detailInfo,
                        'detailPrice' => $detailPrice,
                    ];
                });
            }


            if ($crawler->filter('#quote-nav')->count()) {
                $createButton = $crawler->filter('#quote-nav')->each(function ($item) {
                    $sumray = $item->filter('ul [data-test="SUMMARY"]')->count() ? $item->filter('ul [data-test="SUMMARY"]')->text() : '-';
                    $statistik = $item->filter('ul [data-test="STATISTICS"]')->count() ? $item->filter('ul [data-test="STATISTICS"]')->text() : '-';
                    $analisis = $item->filter('ul [data-test="ANALYSIS"]')->count() ? $item->filter('ul [data-test="ANALYSIS"]')->text() : '-';
                    $finansial = $item->filter('ul [data-test="FINANCIALS"]')->count() ? $item->filter('ul [data-test="FINANCIALS"]')->text() : '-';
                    $profile = $item->filter('ul [data-test="COMPANY_PROFILE"]')->count() ? $item->filter('ul [data-test="COMPANY_PROFILE"]')->text() : '-';
                    return [
                        'sumray' => $sumray,
                        'statistik' => $statistik,
                        'analisis' => $analisis,
                        'finansial' => $finansial,
                        'profile' => $profile,
                    ];
                });
            }


            if ($crawler->filter('#Col1-0-Profile-Proxy')->count()) {
                $data_profile = $crawler->filter('#Col1-0-Profile-Proxy')->each(function ($item) {
                    return
                        [
                            'desc' => $item->filter('div[data-test="prof-desc"]')->each(function ($desc) {
                                return $desc->text();
                            })
                        ];
                });
            }

            return view('Components.Profile', [
                'symbol' => $symbol,
                'information' => $information,
                'createButton' => $createButton,
                'data_profile' => $data_profile,
            ]);
        } catch (\Exception $e) {
            return view('Components.PageError', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
