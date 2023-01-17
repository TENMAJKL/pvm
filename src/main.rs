use std::{fs, env};

#[derive(Clone, Copy, Debug)]
enum CommandKind {
    Psh,
    Pop,
    Out,
    Jmp,
    Add,
    Sub,
    Mul,
    Div,
    Edg,
    Set,
    Get,
    Iot
}

#[derive(Clone, Copy, Debug)]
struct Command {
    kind: CommandKind,
    argument: Option<u8>
}

fn parse(code: String) -> Vec<Command> {
    let mut result: Vec<Command> = Vec::new();

    for line in code.lines() {
        if line == "" || line.starts_with(';') {
            continue;
        }
        let command = line.trim().split(' ').collect::<Vec<&str>>();
        
        result.push(match command[0] {
            "psh" => Command { kind: CommandKind::Psh, argument: Some(command[1].parse::<u8>().unwrap()) },
            "pop" => Command { kind: CommandKind::Pop, argument: None },
            "out" => Command { kind: CommandKind::Out, argument: None },
            "jmp" => Command { kind: CommandKind::Jmp, argument: command.get(1).map(|x| x.parse::<u8>().unwrap())}, 
            "add" => Command { kind: CommandKind::Add, argument: None }, 
            "sub" => Command { kind: CommandKind::Sub, argument: None }, 
            "mul" => Command { kind: CommandKind::Mul, argument: None },
            "div" => Command { kind: CommandKind::Div, argument: None }, 
            "edg" => Command { kind: CommandKind::Edg, argument: None },
            "set" => Command { kind: CommandKind::Set, argument: Some(command[1].parse::<u8>().unwrap()) },
            "get" => Command { kind: CommandKind::Get, argument: Some(command[1].parse::<u8>().unwrap()) },
            "iot" => Command { kind: CommandKind::Iot, argument: None },
            _ => panic!("System panic: unknown command")
        });
    }

    return result;
}

fn interpret(commands: Vec<Command>) {
    let count: u8 = commands.len().try_into().unwrap();
    let mut pointer: u8 = 0;
    let mut command: Command;
    let mut stack: Vec<u8> = Vec::new();

    while pointer < count {
        command = commands[pointer as usize];
        match command.kind {
            CommandKind::Psh => { stack.push(command.argument.unwrap()); },
            CommandKind::Pop => { stack.pop().expect("System panic: unable to reach stack top"); },
            CommandKind::Out => { print!("{}", stack.pop().expect("System panic: unable to reach stack top") as char); },
            CommandKind::Jmp => { pointer = command.argument.or_else(|| stack.pop()).expect("System panic: unable to reach stack top"); continue; },
            CommandKind::Edg => { if stack.last().expect("System panic: unable to reach stack top").eq(&0) { pointer += 1; } },
            CommandKind::Add => { 
                let first = stack.pop().expect("System panic: unable to reach stack top");
                let second = stack.pop().expect("System panic: unable to reach stack top");
                stack.push(second + first);
            },
            CommandKind::Sub => { 
                let first = stack.pop().expect("System panic: unable to reach stack top");
                let second = stack.pop().expect("System panic: unable to reach stack top");
                stack.push(second - first);
            },
            CommandKind::Mul => { 
                let first = stack.pop().expect("System panic: unable to reach stack top");
                let second = stack.pop().expect("System panic: unable to reach stack top");
                stack.push(second * first);
            },
            CommandKind::Div => { 
                let first = stack.pop().expect("System panic: unable to reach stack top");
                let second = stack.pop().expect("System panic: unable to reach stack top");
                stack.push(second / first);
            },
            CommandKind::Set => {
                let value = stack.pop().expect("System panic: unable to reach stack top");
                stack[command.argument.unwrap() as usize] = value;
            },
            CommandKind::Get => {
                let value = stack[command.argument.unwrap() as usize];
                stack.push(value);
            },
            CommandKind::Iot => { print!("{}", stack.pop().expect("System panic: unable to reach stack top")); }
        };
        pointer += 1;
    }
}

fn machine(code: String) {
    let commands = parse(code);
    interpret(commands);
}

fn main() {
    let mut args = env::args();
    let filename = args.nth(1).expect("no file provided");

    let code = fs::read_to_string(filename)
        .expect("File is not readable");
    machine(code);
}
