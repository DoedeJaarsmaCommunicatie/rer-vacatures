import React, { Component } from 'react';
import { Card } from '../../../style/main.css'

export default class File extends Component {
    render() {
        let Element = '';
        if (typeof this.props.file !== 'undefined') {
            Element = this.props.file.length > 0 ? this.fileSX() : this.noFileSX();
        } else {
            Element = this.noFileSX();
        }

        return (
            <Card>
                <div className="title">
                    CV
                </div>

                <div className="content">
                    {Element}
                </div>
            </Card>
        )
    }


    fileSX() {
        return (
            <a href={this.props.file} download>
                CV Downloaden
            </a>
        )
    }

    noFileSX() {
        return <span>CV niet geupload</span>
    }
}
