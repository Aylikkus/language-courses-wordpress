const modal = document.querySelectorAll('.modal');

updateSelect();

function docxModalOpen() {
    const docxModal = document.querySelector('#docx-modal');
    docxModal.style.display = 'flex';
};

window.onclick = function (event) {
    modal.forEach((elem) => {
        if (event.target == elem) {
            elem.style.display = 'none';
        }
    })
};

function updateSelect() {
    const form = document.querySelector('#docx-generate-form');
    const purpose = document.getElementById('purpose-p');
    const rector = document.getElementById('rector-p');
    const cafedry = document.getElementById('cafedry-p');
    const type = form['docType'].value;
    const docClass = getDocClass(type);
    if (docClass == "mu") {
        purpose.style.display = "block";
        rector.style.display = "block";
        cafedry.style.display = "block";
    }
    else if (docClass == "other") {
        purpose.style.display = "none";
        rector.style.display = "none";
        cafedry.style.display = "none";
    };
}

function getDocClass(type) {
    if (['muLab', 'muPrac', 'muKR', 'muKP'].includes(type)) {
        return "mu";
    }
    else if (['labPrac', 'tutorial', 'textbook', 'monograph'].includes(type)) {
        return "other";
    }
    else {
        return "unknown";
    };
};

document.querySelector('#docx-generate-form')['docType'].onchange = function () {
    updateSelect();
};

function getMuDoc(docxForm, imgArrBuf) {
    doc = new docx.Document({
        sections: [{
            properties: {
                titlePage: true,
            },
            children: [

                // Титульный лист

                new docx.Paragraph({
                    children: [
                        new docx.TextRun({
                            text: 'Министерство науки и высшего образования Российской Федерации',
                            size: 30,
                        }),
                    ],
                    alignment: docx.AlignmentType.CENTER,
                }),
                new docx.Paragraph({
                    children: [
                        new docx.TextRun({
                            text: 'Брянский государственный технический университет',
                            size: 32,
                        }),
                    ],
                    alignment: docx.AlignmentType.CENTER,
                }),

                // Гриф

                new docx.Paragraph({
                    spacing: {
                        before: 1000,
                    },
                }),

                new docx.Table({
                    rows: [
                        new docx.TableRow({
                            children: [
                                new docx.TableCell({
                                    children: [
                                        new docx.Paragraph({
                                            children: [
                                                new docx.TextRun({
                                                    text: 'УТВЕРЖДАЮ',
                                                    size: 32,
                                                }),
                                            ],
                                            alignment: docx.AlignmentType.CENTER,
                                        }),
                                    ],
                                    borders: {
                                        top: { style: docx.BorderStyle.NONE },
                                        bottom: { style: docx.BorderStyle.NONE },
                                        left: { style: docx.BorderStyle.NONE },
                                        right: { style: docx.BorderStyle.NONE },
                                    },
                                }),
                            ],
                        }),
                        new docx.TableRow({
                            children: [
                                new docx.TableCell({
                                    children: [
                                        new docx.Paragraph({
                                            children: [
                                                new docx.TextRun({
                                                    text: 'Ректор университета',
                                                    size: 32,
                                                }),
                                            ],
                                            alignment: docx.AlignmentType.CENTER,
                                        }),
                                    ],
                                    borders: {
                                        top: { style: docx.BorderStyle.NONE },
                                        bottom: { style: docx.BorderStyle.NONE },
                                        left: { style: docx.BorderStyle.NONE },
                                        right: { style: docx.BorderStyle.NONE },
                                    },
                                }),
                            ],
                        }),
                        new docx.TableRow({
                            children: [
                                new docx.TableCell({
                                    children: [
                                        new docx.Paragraph({
                                            children: [
                                                new docx.TextRun({
                                                    text: '             ',
                                                    size: 32,
                                                    underline: {},
                                                }),
                                                new docx.TextRun({
                                                    text: docxForm['rector'].value,
                                                    size: 32,
                                                }),
                                            ],
                                            alignment: docx.AlignmentType.CENTER,
                                        }),
                                    ],
                                    borders: {
                                        top: { style: docx.BorderStyle.NONE },
                                        bottom: { style: docx.BorderStyle.NONE },
                                        left: { style: docx.BorderStyle.NONE },
                                        right: { style: docx.BorderStyle.NONE },
                                    },
                                }),
                            ],
                        }),
                        new docx.TableRow({
                            children: [
                                new docx.TableCell({
                                    children: [
                                        new docx.Paragraph({
                                            children: [
                                                new docx.TextRun({
                                                    text: '«',
                                                    size: 32,
                                                }),
                                                new docx.TextRun({
                                                    text: '    ',
                                                    size: 32,
                                                    underline: {},
                                                }),
                                                new docx.TextRun({
                                                    text: '»',
                                                    size: 32,
                                                }),

                                                new docx.TextRun({
                                                    text: '            ',
                                                    underline: {},
                                                    size: 32,
                                                }),
                                                new docx.TextRun({
                                                    text: new Date().getFullYear() + ' г.',
                                                    size: 32,
                                                }),
                                            ],
                                            alignment: docx.AlignmentType.CENTER,
                                        }),
                                    ],
                                    borders: {
                                        top: { style: docx.BorderStyle.NONE },
                                        bottom: { style: docx.BorderStyle.NONE },
                                        left: { style: docx.BorderStyle.NONE },
                                        right: { style: docx.BorderStyle.NONE },
                                    },
                                }),
                            ],
                        }),
                    ],
                    width: {
                        size: 40,
                        type: docx.WidthType.PERCENTAGE,
                    },
                    alignment: docx.AlignmentType.RIGHT,
                }),

                new docx.Paragraph({
                    children: [
                        new docx.TextRun({
                            text: docxForm['title'].value.toUpperCase(),
                            size: 32,
                        })
                    ],
                    alignment: docx.AlignmentType.CENTER,
                    spacing: {
                        before: 2000,
                    },
                }),
                new docx.Paragraph({
                    children: [
                        new docx.TextRun({
                            text: docTypes[docxForm['docType'].value] + docxForm['purpose'].value,
                            size: 32,
                        }),
                    ],
                    alignment: docx.AlignmentType.CENTER,
                    spacing: {
                        before: 1000,
                    },
                }),
            ],
            footers: {
                first: new docx.Footer({
                    children: [
                        new docx.Paragraph({
                            children: [
                                new docx.ImageRun({
                                    type: 'png',
                                    data: imgArrBuf,
                                    transformation: {
                                        width: 75,
                                        height: 75,
                                    },
                                }),
                            ],
                            alignment: docx.AlignmentType.CENTER,
                        }),
                        new docx.Paragraph({
                            children: [
                                new docx.TextRun({
                                    text: "Брянск",
                                    size: "14pt",
                                }),
                            ],
                            alignment: docx.AlignmentType.CENTER,
                        }),
                        new docx.Paragraph({
                            children: [
                                new docx.TextRun({
                                    text: "БГТУ",
                                    size: "14pt",
                                }),
                            ],
                            alignment: docx.AlignmentType.CENTER,
                        }),
                        new docx.Paragraph({
                            children: [
                                new docx.TextRun({
                                    text: new Date().getFullYear(),
                                    size: "14pt",
                                }),
                            ],
                            alignment: docx.AlignmentType.CENTER,
                        }),
                    ],
                })
            }
        }]
    });

    doc.addSection({
        properties: {},
        children: [
            new docx.Paragraph({
                children: [
                    new docx.TextRun({
                        text: 'УДК ' + docxForm['udk'].value,
                        size: 32,
                    }),
                ],
                alignment: docx.AlignmentType.LEFT,
            }),
            new docx.Paragraph({
                children: [
                    new docx.TextRun({
                        text: 'ББК ' + docxForm['bbk'].value,
                        size: 32,
                    }),
                ],
                alignment: docx.AlignmentType.LEFT,
            }),

            new docx.Paragraph({
                children: [
                    new docx.TextRun({
                        text: docxForm['title'].value + " : " +
                            docTypes[docxForm['docType'].value] +
                            docxForm['purpose'].value +
                            docxForm['authors'].value + '. – Брянск : БГТУ',
                        size: 32,
                    })
                ],
                alignment: docx.AlignmentType.JUSTIFIED,
                spacing: {
                    before: 500,
                },
            }),

            new docx.Paragraph({
                children: [
                    new docx.TextRun({
                        text: 'Рекомендовано кафедрой «' + docxForm['cafedry'].value + '» БГТУ ()',
                        size: 32,
                    })
                ],
                alignment: docx.AlignmentType.JUSTIFIED,
                spacing: {
                    before: 500,
                },
            }),

            new docx.Paragraph({
                children: [
                    new docx.TextRun({
                        text: 'Научный редактор',
                        size: 32,
                    }),
                ],
                alignment: docx.AlignmentType.JUSTIFIED,
                spacing: {
                    before: 500,
                },
            }),

            new docx.Paragraph({
                children: [
                    new docx.TextRun({
                        text: 'Компьютерный набор',
                        size: 32,
                    }),
                ],
                alignment: docx.AlignmentType.JUSTIFIED,
                spacing: {
                    before: 500,
                },
            }),

            new docx.Paragraph({
                children: [
                    new docx.TextRun({
                        text: 'Темплан 2023 г., п. 000',
                        size: 32,
                    }),
                ],
                alignment: docx.AlignmentType.RIGHT,
                spacing: {
                    before: 500,
                },
            }),

            new docx.Paragraph({
                children: [
                    new docx.TextRun({
                        text: 'Подписано в печать <>. Формат <>. Усл. печ. л. 0,00.',
                        size: 24,
                    }),
                ],
                alignment: docx.AlignmentType.LEFT,
                spacing: {
                    before: 500,
                },
            }),
            new docx.Paragraph({
                children: [
                    new docx.TextRun({
                        text: 'Брянский государственный технический университет',
                        size: 24,
                    }),
                ],
                alignment: docx.AlignmentType.LEFT,
            }),
            new docx.Paragraph({
                children: [
                    new docx.TextRun({
                        text: 'Адрес.',
                        size: 24,
                    }),
                ],
                alignment: docx.AlignmentType.LEFT,
            }),
            new docx.Paragraph({
                children: [
                    new docx.TextRun({
                        text: 'Кафедра «' + docxForm['cafedry'].value + '», тел. 00-00-00.',
                        size: 24,
                    }),
                ],
                alignment: docx.AlignmentType.LEFT,
            }),
        ],
    });

    return doc;
}

const muPrefix = "Методические указания к выполнению";
const muPostfix = "для студентов очной и заочной форм обучения по укрупненным группам направлений подготовки: "

const docTypes = {
    'muLab': muPrefix + " лабораторной работы " + muPostfix,
    'muPrac': muPrefix + " практической работы " + muPostfix,
    'muKR': muPrefix + " курсовой работы " + muPostfix,
    'muKP': muPrefix + " курсового проекта " + muPostfix,
    'labPrac': "Лабораторный практикум",
    'tutorial': "Учебное пособие",
    'textbook': "Учебник",
    'monograph': "Монография",
};

function getOtherDoc(docxForm) {
    return new docx.Document({
        sections: [{
            properties: {
                titlePage: true,
            },
            children: [

                // Титульный лист

                new docx.Paragraph({
                    children: [
                        new docx.TextRun({
                            text: 'Министерство науки и высшего образования Российской Федерации',
                            size: 30,
                        }),
                    ],
                    alignment: docx.AlignmentType.CENTER,
                }),
                new docx.Paragraph({
                    children: [
                        new docx.TextRun({
                            text: 'Брянский государственный технический университет',
                            size: 32,
                        }),
                    ],
                    alignment: docx.AlignmentType.CENTER,
                }),

                // Авторы

                new docx.Paragraph({
                    children: [
                        new docx.TextRun({
                            text: docxForm['authors'].value,
                            size: 32,
                        }),
                    ],
                    spacing: {
                        before: 1000,
                    },
                    alignment: docx.AlignmentType.CENTER,
                }),

                new docx.Paragraph({
                    children: [
                        new docx.TextRun({
                            text: docxForm['title'].value.toUpperCase(),
                            size: 32,
                            bold: true,
                        })
                    ],
                    alignment: docx.AlignmentType.CENTER,
                    spacing: {
                        before: 2000,
                    },
                }),

                new docx.Paragraph({
                    children: [
                        new docx.TextRun({
                            text: docTypes[docxForm['docType'].value],
                            size: 32,
                        })
                    ],
                    alignment: docx.AlignmentType.CENTER,
                    spacing: {
                        before: 2000,
                    },
                }),
            ],
            footers: {
                first: new docx.Footer({
                    children: [
                        new docx.Paragraph({
                            children: [
                                new docx.TextRun({
                                    text: "Брянск",
                                    size: "14pt",
                                }),
                            ],
                            alignment: docx.AlignmentType.CENTER,
                        }),
                        new docx.Paragraph({
                            children: [
                                new docx.TextRun({
                                    text: "БГТУ",
                                    size: "14pt",
                                }),
                            ],
                            alignment: docx.AlignmentType.CENTER,
                        }),
                        new docx.Paragraph({
                            children: [
                                new docx.TextRun({
                                    text: new Date().getFullYear(),
                                    size: "14pt",
                                }),
                            ],
                            alignment: docx.AlignmentType.CENTER,
                        }),
                    ],
                })
            }
        }]
    });
}

async function generateDocx(pathToLogo) {
    const docxForm = document.querySelector('#docx-generate-form');
    const docClass = getDocClass(docxForm['docType'].value);

    let reader = new FileReader();

    reader.onloadend = function (event) {
        if (docClass == "mu") {
            doc = getMuDoc(docxForm, reader.result);
        }
        else if (docClass == "other") {
            doc = getOtherDoc(docxForm);
        }
        else {
            doc = null;
        }

        docx.Packer.toBlob(doc).then(blob => {
            console.log(blob);
            saveAs(blob, "result.docx");
            console.log("Document created successfully");
        });
    }

    let response = await fetch(pathToLogo);
    let data = await response.blob();
    let metadata = {
        type: 'image/png'
    };

    let file = new File([data], 'logo.png', metadata);

    reader.readAsArrayBuffer(file);
}
