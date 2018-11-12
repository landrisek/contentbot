import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import request from 'sync-request'

var CURRENT = 'current'
var CONTENT = 'content'
var ID = 'contentBot'
var LABELS = 'labels'
var LINKS = {}
var NAME = 'name'
var PLAIN = 'plain'
var SELECT = 'select'
var STATISTICS = 'statistics'
var WRITE = 'write'

export default class ContentBot extends Component {
    constructor(props) {
        super(props);
        this.state = JSON.parse(document.getElementById(ID).getAttribute('data'))
        LINKS = JSON.parse(document.getElementById(ID).getAttribute('data-links'))
    }
    addInsert(key) {
        return <div key={key}><label>{this.state[LABELS][key]}</label><input className='form-control'
                      id={key}
                      onChange={this.update.bind(this)}
                      onKeyPress={this.insert.bind(this)}
                      style={{width: 'auto'}}
                      value={this.state[key]}
        /></div>
    }
    addName() {
        return <div key={NAME}>
                <input className='form-control'
                       id={NAME}
                       onChange={this.type.bind(this)}
                       style={{float:'left',width: 'auto'}}
                       type='text' />
                </div>
    }
    addPreview() {
        return <input className='btn btn-success'
            key={'preview'}
            onClick={this.preview.bind(this)}
            type='submit'
            value={this.state[LABELS].preview} />
    }
    addSelect(key) {
        var options = this.state[CONTENT][key]
        var container = []
        for (var value in options) {
            var id = key + '-' + value
            container.push(<option key={id} value={value}>{options[value]}</option>)
        }
        var id = 'edit-' + key
        container.push(<option key={id} value='edit'>edit...</option>)
        return <select className='form-control'
                       id={key}
                       key={key}
                       onClick={this.click.bind(this)}
                       onChange={this.edit.bind(this)}
                       style={{float:'left',width: 'auto'}} >
                    {container}
                </select>
    }
    addStatistics() {
        var container = []
        for(var statistic in this.state[STATISTICS]) {
            container.push(<div key={statistic}>{statistic}:{this.state[STATISTICS][statistic]}</div>)
        }
        return <div key={STATISTICS}><label>{this.state[LABELS][STATISTICS]}</label><br/>{container}</div>
    }
    addSubmit(key) {
        return <input
            className='btn btn-success'
            key={key}
            onClick={this.submit.bind(this)}
            type='submit'
            value={this.state[LABELS].submit} />
    }
    addText(key) {
        var width = (this.state[CONTENT][key].length + 5) * 8
        width.toString()
        return <input className='form-control'
                      id={key}
                      key={key}
                      onChange={this.change.bind(this)}
                      onClick={this.click.bind(this)}
                      onDragOver={this.drop.bind(this)}
                      onDrop={this.drop.bind(this)}
                      onKeyDown={this.edit.bind(this)}
                      style={{fontWeight:'bold',float:'left',width:width}}
                      value={this.state[CONTENT][key]}/>
    }
    addWrite() {
        return <div key={WRITE}><label>{this.state[LABELS][WRITE]}</label><br/>{this.state[WRITE]}</div>
    }
    attached() {
        var body = [];
        for (var key in this.state[CONTENT]) {
            if('object' == typeof(this.state[CONTENT][key])) {
                body.push(this.addSelect(key))
            } else if('string' == typeof(this.state[CONTENT][key])) {
                body.push(this.addText(key))
            }
        }
        return body;
    }
    change(event) {
        var state = []
        state[CONTENT] = this.state[CONTENT]
        state[CONTENT][event.target.id] = event.target.value
        this.setState(state)
    }
    click(event) {
        this.state[CURRENT] = parseInt(event.target.id) + 1
    }
    drop(event) {
        console.log('drag')
    }
    edit(event) {
        var state = []
        state[CONTENT] = this.state[CONTENT]
        if('Enter' == event.key && /;/.exec(event.target.value)) {
            state[CONTENT][event.target.id] = this.explode(state[CONTENT][event.target.id])
        } else if ('Enter' == event.key && '' == event.target.value) {
            delete state[CONTENT][event.target.id]
        } else if ('Enter' == event.key) {
            state[CONTENT][event.target.id] = state[CONTENT][event.target.id]
        } else if ('edit' == event.target.value) {
            state[CONTENT][event.target.id] = this.implode(state[CONTENT][event.target.id])
        }
        this.setState(state)
    }
    explode(input) {
        var output = new Object()
        var options = input.split(';')
        for(var option in options) {
            if(options[option].length > 0) {
                output[option] = options[option]
            }
        }
        return output
    }
    implode(options) {
        var output = ''
        for(var option in options) {
            if(options[option].length > 0) {
                output += options[option] + ';'
            }
        }
        return output
    }
    insert(event) {
        if('Enter' != event.key || '' === event.target.value) {
            return
        }
        var state = []
        state[CONTENT] = new Object()
        for(var id in this.state[CONTENT]) {
            var key = parseInt(id)
            if(key >= this.state[CURRENT]) {
                state[CONTENT][key + 1] = this.state[CONTENT][key]
            } else {
                state[CONTENT][key] = this.state[CONTENT][key]
            }
        }
        if('select' == event.target.id) {
            state[CONTENT][this.state[CURRENT]] = event.target.value.split(';')
            state[SELECT] = ''
        } else if('plain' == event.target.id) {
            state[CONTENT][this.state[CURRENT]] = event.target.value
            state[PLAIN] = ''
        }
        this.setState(state)
    }
    preview(event) {
        event.preventDefault()
        var state = JSON.parse(request('POST', LINKS.preview, { json: {content: this.state[CONTENT], name: this.state[NAME] } }).getBody('utf8'))
        this.setState(state)
    }
    render() {
        return <div key='content'>
                    <strong>Name</strong>
                    <div>{this.addName()}</div>
                    <div style={{clear:'both'}}></div>
                    <strong>Text</strong>
                    <div>{this.attached()}</div>
                    <div style={{clear:'both'}}></div>
                    <form>{this.addInsert('select')}
                        {this.addInsert('plain')}
                        <hr></hr>{this.addSubmit('submit')}
                        <hr></hr>{this.addPreview()}
                    </form>
                    {this.addWrite()}
                    {this.addStatistics()}
                </div>
    }
    reset() {
        var i=0;
        var data = new Object()
        for(var key in this.state[CONTENT]) {
            if('' != this.state[CONTENT][key]) {
                data[i++] = this.state[CONTENT][key]
            }
        }
        return data
    }
    submit(event) {
        event.preventDefault()
        var data = new Object
        data.content = JSON.stringify(this.reset())
        request('POST', LINKS['submit'], { json: data })
    }
    type(event) {
        var state = []
        state[NAME] = this.state[NAME]
        state[NAME] = event.target.value
        this.setState(state)
    }
    update(event) {
        var state = []
        if('select' == event.target.id) {
            state[SELECT] = event.target.value
        } else {
            state[PLAIN] = event.target.value
        }
        this.setState(state)
    }
}
ReactDOM.render(<ContentBot />, document.getElementById(ID))